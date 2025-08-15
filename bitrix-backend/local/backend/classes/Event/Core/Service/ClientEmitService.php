<?php

namespace Event\Core\Service;

use Event\Core\Entity\ClientEmit;
use Main\Service\BaseService;
use function TG\Event\Core\Service\FormatDate;

class ClientEmitService extends BaseService
{
    /**
     * @var ClientEmit[]
     */
    public $queue = [];
    public $config = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('CLIENT_EMIT');
    }

    function getEmitClass($eventName)
    {
        $types = $this->container->applyFiltersCached('client_emit:types', []);
        return $types[$eventName] ?? ClientEmit::class;
    }

    /**
     * @return ClientEmit
     */
    function createEmit($eventName, $fields = [])
    {
        $cls = $this->getEmitClass($eventName);
        $fields['eventName'] = $eventName;
        /**
         * @var ClientEmit $entity
         */
        $entity = new $cls($fields);
        $this->fillCommonData($entity);
        $this->fillUserData($entity);
        $entity->createRelated();;
        return $entity;
    }

    function addToQueue($emit)
    {
        $this->queue[] = $emit;
    }

    function onShutdown()
    {
        $this->queueCommit();
    }

    function queueCommit()
    {
        $items = [];

        foreach ($this->queue as $entity) {
            $this->fillBeforeSend($entity);
            $entity->getDto();
            $items[] = $entity->getDto();
        }

        $this->queue = [];

        if (!empty($items)) {
            $redis = $this->container->getRedisService()->getConnection();
            if ($redis) {
                $redis->publish('client:emits', json_encode($items));
            }
        }
    }

    function fillBeforeSend(&$entity)
    {
        return $entity;
    }

    function fillCommonData(&$entity)
    {
        $entity['createdAt'] = FormatDate('FULL', time());
        return $entity;
    }

    function fillUserData(&$entity)
    {
        $appClientService = $this->container->getAppClientService();
        $clientId = $appClientService->getClientId();
        $userId = intval($this->container->getUserId());

        if (!$entity['targetClientId'])
            $entity['targetClientId'] = $clientId;

        if (!$entity['targetUserId'])
            $entity['targetUserId'] = $userId;

        return $entity;
    }
}
