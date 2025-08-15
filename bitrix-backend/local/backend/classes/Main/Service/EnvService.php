<?php

namespace Main\Service;

use Main\Request\AppScope;

class EnvService extends BaseService
{
    var $env = [];

    function getEnvName()
    {
        return $this->env['env'];
    }

    function loadConfig($path)
    {
        $configService = $this->container->getConfigService();

        $envFiles = [
            '.env.' . $this->env['env'],
            '.env.' . $this->env['mode'],
        ];

        foreach ($envFiles as $file) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
            if (file_exists($filePath)) {
                $dotenv = \Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'], $file);
                $envData = $dotenv->load();
                $envDataTree = $configService->buildTree($envData);
                $configService->mergeConfigData([
                    'ENV' => $envDataTree,
                ]);
            }
        }

        $suggestions = [
            ['app', 'mode', 'env'],
            ['app', 'mode'],
            ['app', 'env'],
            ['app'],
        ];

        foreach ($suggestions as $suggestion) {

            $filenameParts = [];

            foreach ($suggestion as $part) {
                if (empty($this->env[$part])) {
                    $filenameParts = [];
                    break;
                }
                $filenameParts[] = $this->env[$part];
            }

            if (!empty($filenameParts)) {
                $filename = join('-', $filenameParts);
                $filepath = $path . '/' . $filename . '.php';
                if (file_exists($filepath)) {
                    $configService->mergeConfigFile($filepath, [
                        'config' => $configService->config
                    ]);
                    break;
                }
            }
        }

        if ($envConfig = $this->getEnv('config')) {
            $configService->mergeConfigData($envConfig);
        }

        return $this;
    }

    function getEnv($name, $def = null)
    {
        return $this->env[$name] ?? $def;
    }

    function applyAppScope(AppScope $app)
    {
        if ($app->getAppName()) {
            $this->env['app'] = $app->getAppName();
        }

        if ($app->getEnv()) {
            $this->setEnvName($app->getEnv());
        }

        if ($app->getMode()) {
            $this->env['mode'] = $app->getMode();
        }

        if (!defined('TG_APP_CLASS'))
            define('TG_APP_CLASS', $app->getAppClass());

        if (!defined('TG_APP_NAME'))
            define('TG_APP_NAME', $app->getAppName());

        return $this;
    }

    function setEnvName($envName)
    {
        $this->env['env'] = $envName;

        define('SITE_ENV', $envName);

        return $this;
    }
}


