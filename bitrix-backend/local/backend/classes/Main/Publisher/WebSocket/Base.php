<?php

namespace Main\Publisher\WebSocket;

use Main\DI\Containerable;

abstract class Base
{
    use Containerable;

    function emitClientEvent($eventType, $eventData, $receivers = [])
    {
        $receiversResult = [];

        foreach ($receivers as $receiversKey => $receiversValue) {
            if (!is_numeric($receiversKey)) {
                $receiversResult[] = $receiversKey . '.' . $receiversValue;
            } else {
                $receiversResult[] = $receiversValue;
            }
        }

        $data = [
            'type' => 'client-event',
            'receivers' => $receiversResult,
            'event' => $eventType,
            'data' => $eventData
        ];

        $this->publish($data);
    }

    abstract function publish($data, $channel = 'socket:messages');
}


