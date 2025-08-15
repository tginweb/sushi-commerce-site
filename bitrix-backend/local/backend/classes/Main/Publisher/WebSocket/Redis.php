<?php

namespace Main\Publisher\WebSocket;

class Redis extends Base
{
    public $redis;

    function __construct($conf)
    {
        $this->redis = $this->container->getRedisService()->getConnection($conf['connection']);
    }

    function publish($data, $channel = 'websockets')
    {
        $data = json_encode($data);

        $this->redis->publish($channel, $data);
    }
}


