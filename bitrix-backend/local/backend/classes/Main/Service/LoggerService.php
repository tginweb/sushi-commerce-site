<?php

namespace Main\Service;

use Main\Entity\Logger\LoggerEvent;
use TG\Main\Helper;
use function TG\Main\Service\randString;

class LoggerService extends BaseService
{

    /**
     * @var LoggerEvent
     */
    public $parentEvent;

    public $queue = [];

    public $requestId;

    public $rootEvent = null;
    public $config = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('LOGGER');
    }

    function onShutdown()
    {
        $this->queueCommit();
    }

    function queueCommit()
    {
        return;

        $events = [];

        foreach ($this->queue as $event) {
            $this->computeEventData($event);
            $events[] = $event->getSaveData();
        }

        $this->queue = [];

        if (!empty($events)) {
            $redis = $this->container->getRedisService()->getConnection();
            $filteredList = [];
            foreach ($events as &$event) {
                if ($event['name'] === 'API_GQL') {
                    if (count($events) === 1) continue;
                }
                $event['json'] = json_encode($event);
                $filteredList[] = $event;
            }
            $redis->publish('logger:events', json_encode($filteredList));
        }
    }

    function computeEventData(&$event)
    {
        $appClientService = $this->container->getAppClientService();

        $user = $this->container->getUser();
        $vorder = $this->container->getVorder();

        $sess = $event['sess'] ?? [];
        $app = $event['app'] ?? [];
        $request = $event['request'] ?? [];

        $sess += [
            'sessionId' => session_id(),
            'fuserId' => $this->container->getSession()->getFuserId(),
            'vorderId' => $vorder ? $vorder->getId() : null,
            'ip' => \Main\Helper\Http::getRealIp(),
        ];

        if ($user) {
            $sess['authorized'] = true;
            $sess['userId'] = intval($user->id);
            $sess['phone'] = $user->getPhone();
            $sess['email'] = $user->getEmail();
        } else {
            $sess['authorized'] = false;
            $sess['phone'] = $vorder ? $vorder->getPhoneSaved() : null;
        }

        $app += [
            'id' => 'bx',
            'env' => 'dev'
        ];

        $request += [
            'rid' => $this->getRequestId()
        ];

        $event['sess'] = $sess;
        $event['app'] = $app;
        $event['request'] = $request;
        $event['clientContext'] = $appClientService->getClientContext();
        $event['clientId'] = $appClientService->getClientId();

        /*
        foreach ($this->getApiContext() as $key => $val) {
            $event['api'.ucfirst($key)] = $val;
        }
        */

        return $event;
    }

    function getRequestId()
    {
        if (!isset($this->requestId))
            $this->requestId = randString(6);

        return $this->requestId;
    }

    function addEvent($fields = [])
    {
        $fields += [
            'type' => 'log'
        ];

        $parentEvent = $this->getParentEvent();

        if ($parentEvent) {
            $event = $parentEvent->child();
        } else {
            $event = new LoggerEvent();
            $event->setService($this);
        }

        $event->setFields($fields);

        return $event;
    }

    function getParentEvent()
    {
        return $this->parentEvent;
    }

    function setParentEvent($event)
    {
        $this->parentEvent = $event;
        return $this;
    }

    function getRootEvent()
    {
        return $this->rootEvent;
    }

    function setRootEvent($event)
    {
        $this->rootEvent = $event;
    }

    function addToQueue(LoggerEvent $event)
    {
        $this->queue[] = $event;
    }
}
