<?php

namespace Push\Pub\Api\Resolver;

use Main\Graphql\Resolver\BaseResolver;
use Main\Graphql\Types;
use Main\Model\Response;
use Push\Core\Service\PushService;

class PushResolver extends BaseResolver
{
    function __construct()
    {
        $gql = $this->getGraphql();

        // Мутация для подписки на push-уведомления
        $gql->mutation('push_subscribe', [
            'type' => $this->typeResponseWithPayload('PushSubscribe', Types::boolean()),
            'args' => [
                'endpoint' => Types::nonNull(Types::string()),
                'p256dhKey' => Types::nonNull(Types::string()),
                'authToken' => Types::nonNull(Types::string()),
            ],
            'resolve' => [$this, 'mutationSubscribe']
        ]);

        // Мутация для отписки от push-уведомлений
        $gql->mutation('push_unsubscribe', [
            'type' => $this->typeResponseWithPayload('PushUnsubscribe', Types::boolean()),
            'args' => [
                'subscriptionId' => Types::nonNull(Types::string()),
            ],
            'resolve' => [$this, 'mutationUnsubscribe']
        ]);

        // Запрос для получения VAPID публичного ключа
        $gql->query('push_vapid_public_key', [
            'type' => Types::string(),
            'resolve' => [$this, 'queryVapidPublicKey']
        ]);

        // Запрос для получения подписчиков пользователя
        $gql->query('push_user_subscribers', [
            'type' => Types::listOf(Types::get('PushSubscriber')),
            'resolve' => [$this, 'queryUserSubscribers']
        ]);

        // Мутация для отправки тестового уведомления (только для админов)
        $gql->mutation('push_send_test', [
            'type' => $this->typeResponseWithPayload('PushSendTest', Types::boolean()),
            'args' => [
                'title' => Types::nonNull(Types::string()),
                'body' => Types::nonNull(Types::string()),
                'data' => Types::JSON(),
            ],
            'resolve' => [$this, 'mutationSendTest']
        ]);
    }

    /**
     * Подписаться на push-уведомления
     */
    function mutationSubscribe($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();

        if (!$user || !$user['ID']) {
            $response->addError('Пользователь не авторизован');
            return $response->getGraphqlJson();
        }

        try {
            $pushService = $this->container->get(PushService::DI_SERVICE);

            $subscriber = $pushService->subscribe(
                $user['ID'],
                $args['endpoint'],
                $args['p256dhKey'],
                $args['authToken']
            );

            if ($subscriber) {
                $response->addSuccess('Успешно подписались на push-уведомления');
                $response->setPayload(true);
            } else {
                $response->addError('Ошибка при подписке на push-уведомления');
            }

        } catch (\Exception $e) {
            $response->addError('Ошибка при подписке: ' . $e->getMessage());
        }

        return $response->getGraphqlJson();
    }

    /**
     * Отписаться от push-уведомлений
     */
    function mutationUnsubscribe($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();

        if (!$user || !$user['ID']) {
            $response->addError('Пользователь не авторизован');
            return $response->getGraphqlJson();
        }

        try {
            $pushService = $this->container->get(PushService::DI_SERVICE);

            $result = $pushService->unsubscribe($args['subscriptionId']);

            if ($result) {
                $response->addSuccess('Успешно отписались от push-уведомлений');
                $response->setPayload(true);
            } else {
                $response->addError('Подписка не найдена');
            }

        } catch (\Exception $e) {
            $response->addError('Ошибка при отписке: ' . $e->getMessage());
        }

        return $response->getGraphqlJson();
    }

    /**
     * Получить VAPID публичный ключ
     */
    function queryVapidPublicKey()
    {
        $pushService = $this->container->get(PushService::DI_SERVICE);
        return $pushService->getVapidPublicKey();
    }

    /**
     * Получить подписчиков пользователя
     */
    function queryUserSubscribers()
    {
        $user = $this->container->getUser();

        if (!$user || !$user['ID']) {
            return [];
        }

        $pushService = $this->container->get(PushService::DI_SERVICE);
        return $pushService->getUserSubscribers($user['ID']);
    }

    /**
     * Отправить тестовое уведомление (только для админов)
     */
    function mutationSendTest($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();

        if (!$user || !$user['ID']) {
            $response->addError('Пользователь не авторизован');
            return $response->getGraphqlJson();
        }

        // Проверяем права администратора
        if (!$this->container->getUserGuard()->isAdmin()) {
            $response->addError('Недостаточно прав для отправки уведомлений');
            return $response->getGraphqlJson();
        }

        try {
            $pushService = $this->container->get(PushService::DI_SERVICE);

            $results = $pushService->sendToUser(
                $user['ID'],
                $args['title'],
                $args['body'],
                $args['data'] ?? []
            );

            $successCount = 0;
            $errorCount = 0;

            foreach ($results as $result) {
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            if ($successCount > 0) {
                $response->addSuccess("Отправлено уведомлений: {$successCount}");
                if ($errorCount > 0) {
                    $response->addError("Ошибок отправки: {$errorCount}");
                }
                $response->setPayload(true);
            } else {
                $response->addError('Не удалось отправить ни одного уведомления');
            }

        } catch (\Exception $e) {
            $response->addError('Ошибка при отправке: ' . $e->getMessage());
        }

        return $response->getGraphqlJson();
    }
}
