<?php

namespace Main\Service;

use Exception;

class WebSocketService extends BaseService
{
    public $config = [];
    public $publishers = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('WEBSOCKET');

    }

    function emitClientEventBySession($eventType, $eventData)
    {
        $receivers = [];

        $userId = $this->container->getUserId();

        if ($userId) {
            $receivers['userId'] = $userId;
        }

        $this->emitClientEvent($eventType, $eventData, $receivers);
    }

    function emitClientEvent($eventType, $eventData, $receivers = [])
    {
        $publisher = $this->getPublisher();

        $publisher->emitClientEvent($eventType, $eventData, $receivers);
    }

    function getPublisher($name = 'default')
    {
        if ($this->publishers[$name]) {
            $publisher = $this->publishers[$name];
        } else if ($info = $this->config['PUBLISHERS'][$name]) {
            $publisher = $this->publishers[$name] = new $info['class']($info);
        }

        if (!$publisher)
            throw new Exception('Publisher not found');

        return $publisher;
    }
}


