<?php

namespace Main\Service;

use Exception;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService extends BaseService
{
    public $config = [];
    public $connections = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('REDIS');
    }

    function getConnection($name = 'default')
    {
        if (isset($this->connections[$name])) {
            $connection = $this->connections[$name];
        } else if (isset($this->config['CONNECTIONS'][$name])) {
            $params = $this->config['CONNECTIONS'][$name];
            $params += [
                'dsn' => 'redis://localhost',
            ];
            $this->connections[$name] = $connection = RedisAdapter::createConnection($params['dsn']);
        }

        if (!$connection)
            throw new Exception('Redis connection not found');

        return $connection;
    }

}


