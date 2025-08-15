<?php

namespace Main\Service;

use Exception;
use MongoDB\Driver;

class MongoService extends BaseService
{
    public $config = [];
    public $connections = [];
    public $clients = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('MONGO');
    }

    function getConnectionDb($connectionName = 'default', $defaultDb = null)
    {
        $conf = $this->getConnectionConfig($connectionName);
        return $conf && isset($conf['db']) ? $conf['db'] : $defaultDb;
    }

    function getConnectionConfig($name = 'default')
    {
        return $this->config['CONNECTIONS'][$name] ?? [];
    }

    function getDatabase($name = 'default')
    {
        return $this->config['CONNECTIONS'][$name]['db'] ?? 'site';
    }

    function getConnection($name = 'default')
    {

        if (isset($this->connections[$name])) {
            $connection = $this->connections[$name];
        } else if (isset($this->config['CONNECTIONS'][$name])) {

            $params = $this->config['CONNECTIONS'][$name];

            $params += [
                'dsn' => 'mongodb://localhost:27017',
                'options' => []
            ];

            $this->connections[$name] = $connection = new Driver\Manager($params['dsn'], $params['options']);
        }

        if (!$connection)
            throw new Exception('Mongo connection not found');

        return $connection;
    }

    function getClient($name = 'default')
    {

        if (isset($this->connections[$name])) {
            $connection = $this->connections[$name];
        } else if (isset($this->config['CONNECTIONS'][$name])) {

            $params = $this->config['CONNECTIONS'][$name];

            $params += [
                'dsn' => 'mongodb://localhost:27017',
                'options' => []
            ];

            $this->connections[$name] = $connection = new Driver\Manager($params['dsn'], $params['options']);
        }

        if (!$connection)
            throw new Exception('Mongo connection not found');

        return $connection;
    }

}


