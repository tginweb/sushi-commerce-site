<?php

namespace Main\Request;

class AppScope
{
    public $configDir;

    public $hostname;
    public $requestUrl;

    public $appName;
    public $appClass;

    public $env;
    public $mode;
    public $params = [];

    function __construct(
        $configDir = null,
        $hostname = null,
        $requestUrl = null
    )
    {
        if (!is_dir($configDir))
            throw new \Exception('Config directory does not exist');

        $this->configDir = $configDir;
        $this->hostname = $hostname ?? explode(':', $_SERVER['HTTP_HOST'])[0];
        $this->requestUrl = $requestUrl ?? $_SERVER['REQUEST_URI'];
    }

    function getConfigDir()
    {
        return $this->configDir;
    }

    function setConfigDir($dir)
    {
        $this->configDir = $dir;
        return $this;
    }

    function setAppClass($appClass)
    {
        $this->appClass = $appClass;
        return $this;
    }

    function setAppName($name)
    {
        $this->appName = $name;
        return $this;
    }

    function setMode($mode = null)
    {
        $this->mode = $mode;
        return $this;
    }

    function setEnv($env)
    {
        $this->env = $env;
        return $this;
    }

    function getEnv()
    {
        return $this->env;
    }

    function getMode()
    {
        return $this->mode;
    }

    function getAppName()
    {
        return $this->appName;
    }

    function getAppClass()
    {
        return $this->appClass;
    }

    function getRequestUrl()
    {
        return $this->requestUrl;
    }

    function getHostname()
    {
        return $this->hostname;
    }

    function loadFromRulesFile($stagesPath = null)
    {
        if (!$stagesPath)
            $stagesPath = $this->getConfigDir() . '/stages.php';;

        if (file_exists($stagesPath)) {
            $stagesSchema = include($stagesPath);
            if (is_array($stagesSchema)) {
                $scope = $this->findRulesScope($stagesSchema, $this->hostname, $this->requestUrl);
                if ($scope) {
                    if (isset($scope['appClass'])) {
                        $this->setAppClass($scope['appClass']);
                    }
                    if (isset($scope['appName'])) {
                        $this->setAppName($scope['appName']);
                    }
                    if (isset($scope['env'])) {
                        $this->setEnv($scope['env']);
                    }
                    if (isset($scope['mode'])) {
                        $this->setMode($scope['mode']);
                    }
                }
            }
        }
    }

    function findRulesScope($schema, $hostname, $url)
    {
        $scope = $schema;

        $currentMatched = false;
        $childrenMatched = false;

        if (!empty($schema['domains'])) {
            $domains = (array)$schema['domains'];
            foreach ($domains as $domain) {
                if (($domain[0] === '/') && preg_match($domain, $hostname) || $domain === $hostname) {
                    $currentMatched = true;
                    break;
                }
            }
            if (!$currentMatched) {
                return false;
            }
        }

        if (!empty($schema['url'])) {
            if (!preg_match($schema['url'], $url)) {
                return false;
            }
        }

        if (!empty($schema['variants'])) {
            foreach ($schema['variants'] as $variant) {
                $res = $this->findRulesScope($variant, $hostname, $url);

                if ($res !== false) {
                    $scope = $res + $scope;
                    $childrenMatched = true;
                    break;
                }
            }
        }

        if ($currentMatched || $childrenMatched) {
            unset($scope['domains']);
            unset($scope['variants']);
            return $scope;
        }

        return false;
    }
}




