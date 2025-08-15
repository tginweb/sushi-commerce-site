<template>
  <q-btn
    :label="subscribed ? 'Отписаться от уведомлений' : 'Включить уведомления'"
    :color="subscribed ? 'negative' : 'primary'"
    :loading="loading"
    @click="togglePush"
    class="q-mb-md"
  />
  <q-btn
    v-if="isAdmin"
    label="Тестовое уведомление"
    color="secondary"
    class="q-ml-md"
    @click="sendTestPush"
    :loading="testLoading"
  />
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { subscribeUserToPush, unsubscribeUserFromPush } from '@/core/push/webPush';
import { useUserStore } from '@/modules/user/store/user';
import { useAlerts } from '@/core/store/alerts';
import { useGraphql } from '@/core/graphql/service';

const subscribed = ref(false);
const loading = ref(false);
const testLoading = ref(false);
const { showAlert } = useAlerts();
const userStore = useUserStore();
const { query, mutation } = useGraphql();

const isAdmin = userStore.user?.IS_ADMIN === true || userStore.user?.IS_ADMIN === 'Y';

async function checkSubscription() {
  if ('serviceWorker' in navigator) {
    const reg = await navigator.serviceWorker.getRegistration('/service-worker.js');
    if (reg) {
      const sub = await reg.pushManager.getSubscription();
      subscribed.value = !!sub;
    }
  }
}

async function togglePush() {
  loading.value = true;
  try {
    if (subscribed.value) {
      await unsubscribeUserFromPush();
      showAlert({ type: 'info', message: 'Вы отписались от уведомлений' });
      subscribed.value = false;
    } else {
      await subscribeUserToPush();
      showAlert({ type: 'success', message: 'Подписка на уведомления активирована' });
      subscribed.value = true;
    }
  } catch (e: any) {
    console.log('e1', e)
    showAlert({ type: 'error', message: e.message || 'Ошибка push-подписки' });
  } finally {
    loading.value = false;
  }
}

async function sendTestPush() {
  testLoading.value = true;
  try {
    await mutation({
      push_send_test: {
        title: 'Тестовое уведомление',
        body: 'Это тестовое push-сообщение',
        data: { url: window.location.href },
      },
    });
    showAlert({ type: 'success', message: 'Тестовое уведомление отправлено' });
  } catch (e: any) {
    console.log('e2', e)
    showAlert({ type: 'error', message: e.message || 'Ошибка отправки тестового уведомления' });
  } finally {
    testLoading.value = false;
  }
}

onMounted(checkSubscription);
</script>
