import { useGraphql } from '@/core/graphql/service';

const SERVICE_WORKER_PATH = '/service-worker.js';

export async function getVapidPublicKey(): Promise<string> {
  const { query } = useGraphql();
  const res = await query({ push_vapid_public_key: true });
  return res?.push_vapid_public_key || '';
}

export async function registerServiceWorker(): Promise<ServiceWorkerRegistration | null> {
  if ('serviceWorker' in navigator) {
    return await navigator.serviceWorker.register(SERVICE_WORKER_PATH);
  }
  return null;
}

export async function subscribeUserToPush(): Promise<PushSubscription | null> {
  const registration = await registerServiceWorker();
  if (!registration) return null;

  const vapidKey = await getVapidPublicKey();
  if (!vapidKey) throw new Error('VAPID public key not found');

  const subscription = await registration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: urlBase64ToUint8Array(vapidKey),
  });

  // Отправляем токен на сервер
  await sendSubscriptionToServer(subscription);
  return subscription;
}

export async function unsubscribeUserFromPush(): Promise<boolean> {
  const registration = await registerServiceWorker();
  if (!registration) return false;
  const subscription = await registration.pushManager.getSubscription();
  if (subscription) {
    await removeSubscriptionFromServer(subscription);
    return subscription.unsubscribe();
  }
  return false;
}

export async function sendSubscriptionToServer(subscription: PushSubscription) {
  const { mutation } = useGraphql();
  const { endpoint, keys } = subscription.toJSON();
  await mutation({
    push_subscribe: {
      endpoint,
      p256dhKey: keys.p256dh,
      authToken: keys.auth,
    },
  });
}

export async function removeSubscriptionFromServer(subscription: PushSubscription) {
  const { mutation } = useGraphql();
  const { endpoint } = subscription.toJSON();
  // Для отписки нужен subscriptionId, но можно использовать endpoint как идентификатор
  await mutation({
    push_unsubscribe: {
      subscriptionId: endpoint,
    },
  });
}

function urlBase64ToUint8Array(base64String: string) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}
