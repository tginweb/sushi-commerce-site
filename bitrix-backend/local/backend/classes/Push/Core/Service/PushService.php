<?php

namespace Push\Core\Service;

use Main\DI\Containerable;
use Main\Service\BaseService;
use Minishlink\WebPush\Message;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Push\Core\Entity\PushSubscriberModel;

class PushService extends BaseService
{
    use Containerable;

    private $webPush;
    private $vapidKeys;

    public function __construct()
    {
        $this->initWebPush();
    }

    private function initWebPush()
    {
        $config = $this->container->getConfigService();

        $this->vapidKeys = [
            'VAPID' => [
                'subject' => $config->get('PUSH.VAPID.SUBJECT', 'mailto:info@example.com'),
                'publicKey' => $config->get('PUSH.VAPID.PUBLIC_KEY'),
                'privateKey' => $config->get('PUSH.VAPID.PRIVATE_KEY'),
            ],
        ];

        $this->webPush = new WebPush($this->vapidKeys);
    }

    /**
     * Подписать пользователя на push-уведомления
     */
    public function subscribe($userId, $endpoint, $p256dhKey, $authToken)
    {
        // Проверяем, существует ли уже подписка с таким endpoint
        $existingSubscriber = PushSubscriberModel::query()
            ->filterByEndpoint($endpoint)
            ->first();

        if ($existingSubscriber) {
            // Обновляем существующую подписку
            $existingSubscriber['UF_USER_ID'] = $userId;
            $existingSubscriber['UF_P256DH_KEY'] = $p256dhKey;
            $existingSubscriber['UF_AUTH_TOKEN'] = $authToken;
            $existingSubscriber['UF_ACTIVE'] = 'Y';
            $existingSubscriber->save();

            return $existingSubscriber;
        }

        // Создаем новую подписку
        $subscriber = PushSubscriberModel::create([
            'UF_USER_ID' => $userId,
            'UF_ENDPOINT' => $endpoint,
            'UF_P256DH_KEY' => $p256dhKey,
            'UF_AUTH_TOKEN' => $authToken,
            'UF_ACTIVE' => 'Y',
        ]);

        $subscriber->save();

        return $subscriber;
    }

    /**
     * Отписать пользователя от push-уведомлений
     */
    public function unsubscribe($subscriptionId)
    {
        $subscriber = PushSubscriberModel::query()
            ->filterBySubscriptionId($subscriptionId)
            ->first();

        if ($subscriber) {
            $subscriber['UF_ACTIVE'] = 'N';
            $subscriber->save();
            return true;
        }

        return false;
    }

    /**
     * Отправить уведомление конкретному пользователю
     */
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $subscribers = PushSubscriberModel::query()
            ->filterByUser($userId)
            ->filterActive()
            ->getList();

        $results = [];

        foreach ($subscribers as $subscriber) {
            $results[] = $this->sendToSubscriber($subscriber, $title, $body, $data);
        }

        return $results;
    }

    /**
     * Отправить уведомление всем активным подписчикам
     */
    public function sendToAll($title, $body, $data = [])
    {
        $subscribers = PushSubscriberModel::query()
            ->filterActive()
            ->getList();

        $results = [];

        foreach ($subscribers as $subscriber) {
            $results[] = $this->sendToSubscriber($subscriber, $title, $body, $data);
        }

        return $results;
    }

    /**
     * Отправить уведомление конкретному подписчику
     */
    private function sendToSubscriber($subscriber, $title, $body, $data = [])
    {
        try {
            $subscription = Subscription::create([
                'endpoint' => $subscriber->getEndpoint(),
                'keys' => [
                    'p256dh' => $subscriber->getP256dhKey(),
                    'auth' => $subscriber->getAuthToken(),
                ],
            ]);

            $payload = json_encode([
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'timestamp' => time(),
            ]);

            $message = new Message($payload, [
                'TTL' => 86400, // 24 часа
                'urgency' => 'high',
                'topic' => 'push-notification',
            ]);

            $report = $this->webPush->sendOneNotification($subscription, $message);

            if (!$report->isSuccess()) {
                // Если подписка недействительна, деактивируем её
                if ($report->isSubscriptionExpired()) {
                    $subscriber['UF_ACTIVE'] = 'N';
                    $subscriber->save();
                }

                return [
                    'success' => false,
                    'error' => $report->getReason(),
                    'subscription_id' => $subscriber['UF_SUBSCRIPTION_ID'],
                ];
            }

            return [
                'success' => true,
                'subscription_id' => $subscriber['UF_SUBSCRIPTION_ID'],
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'subscription_id' => $subscriber['UF_SUBSCRIPTION_ID'],
            ];
        }
    }

    /**
     * Получить публичный VAPID ключ для фронтенда
     */
    public function getVapidPublicKey()
    {
        return $this->vapidKeys['VAPID']['publicKey'];
    }

    /**
     * Получить количество активных подписчиков
     */
    public function getActiveSubscribersCount()
    {
        return PushSubscriberModel::query()
            ->filterActive()
            ->count();
    }

    /**
     * Получить подписчиков пользователя
     */
    public function getUserSubscribers($userId)
    {
        return PushSubscriberModel::query()
            ->filterByUser($userId)
            ->filterActive()
            ->getList();
    }
}
