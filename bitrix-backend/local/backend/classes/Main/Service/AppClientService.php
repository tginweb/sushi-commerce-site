<?php

namespace Main\Service;

use Main\Entity\User\AppClientModel;
use Main\Helper\Http;

class AppClientService extends BaseService
{
    /**
     * @var AppClientModel[]
     */
    public $queueUpdated = [];

    public $clientContext = [];

    function register($scopes = [])
    {
        $clientContext = Http::getRequestHttpHeader('CLIENT_CONTEXT');
        if (!empty($clientContext)) {
            $clientContextData = json_decode($clientContext, true);
            if ($clientContextData)
                $this->setClientContext($clientContextData);
        }
        register_shutdown_function([$this, 'onShutdown']);
    }

    static function callAuthorizeByHeaderToken()
    {
        $service = static::container()->getAppClientService();
        $service->authorizeByHeaders();
    }

    function authorizeByHeaders($create = false)
    {
        global $APPLICATION;
        global $USER;

        if (strpos($APPLICATION->GetCurPage(), '/bitrix/') === false) {
            $appClient = $this->getCurrentAppClient($create);
            if ($appClient && $appClient->getUserId()) {
                $USER->Authorize($appClient->getUserId());
            }
        }
    }

    function getCurrentAppClient($create = false)
    {
        return $this->ensureAppClient($this->getClientId(), $this->getClientToken(), $create);
    }

    function ensureAppClient($clientId, $clientToken, $create = false)
    {
        global $USER;

        if (!$clientId)
            return null;

        $filter = [
            'UF_CLIENT_ID' => $clientId,
        ];

        if ($clientToken) {
            $filter[] = [
                'LOGIC' => 'OR',
                'UF_TOKEN' => $clientToken,
            ];
        } else {
            $filter = null;
        }

        if ($filter)
            $appClient = AppClientModel::query()->filter($filter)->first();

        if (!$appClient) {

            if (!$create)
                return;

            $sessionId = session_id();
            $userId = $USER->GetID();
            $userId = intval($userId);

            $appClient = AppClientModel::create([
                'UF_CLIENT_ID' => $clientId,
                'UF_SESSION_ID' => $sessionId,
                'UF_USER_ID' => $userId,
            ]);

            $appClient->ensureToken();
            $appClient->save();
        } else {
            // $appClient['UF_SESSION_ID'] = $sessionId;
        }

        return $appClient;
    }

    function getClientId()
    {
        return Http::getRequestHttpHeader('CLIENT_ID', 'CLIENTID');
    }

    function getClientToken()
    {
        return preg_replace('/\s*Bearer\s*/', '', Http::getRequestHttpHeader('AUTHORIZATION'));
    }



    function getClientContext()
    {
        return $this->clientContext;
    }

    function setClientContext($data = [])
    {
        $this->clientContext = $data ?: [];
    }

    function getClientContextParam($name)
    {
        return $this->clientContext[$name];
    }

    function getClientAppId()
    {
        return Http::getRequestHttpHeader('APP_ID');
    }

    function getClientHeaderData()
    {
        return [
            'token' => $this->getClientToken(),
            'clientId' => $this->getClientId(),
        ];
    }

    function queueUpdatedAdd(AppClientModel $appClient)
    {
        $this->queueUpdated[] = $appClient;
    }

    function onShutdown()
    {
        $this->queueCommit();
    }

    function queueCommit()
    {
        $items = [];

        foreach ($this->queueUpdated as $entity) {
            $items[] = $entity->getClientData();
        }

        $this->queueUpdated = [];

        if (!empty($items)) {
            $redis = $this->container->getRedisService()->getConnection();
            if ($redis)
                $redis->publish('app-client:updated', json_encode($items));
        }
    }
}


