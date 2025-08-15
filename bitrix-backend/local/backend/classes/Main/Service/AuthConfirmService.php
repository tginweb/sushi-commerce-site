<?php

namespace Main\Service;

use Exception;
use Main\Provider\AuthConfirm\Base;

class AuthConfirmService extends BaseService
{
    public $providers = [];
    public $config = [];

    function __construct()
    {
        $this->config = $this->container->getConfigService()->get('AUTH_CONFIRM');
    }

    function getProviders()
    {
        $result = [];
        foreach ($this->config['PROVIDERS'] as $code => $providerInfo) {
            $result[$code] = $this->getProvider($code);
        }
        return $result;
    }

    /**
     * @return Base
     */
    function getProvider($name = 'default')
    {
        if (isset($this->providers[$name])) {
            $provider = $this->providers[$name];
        } else if ($providerInfo = $this->config['PROVIDERS'][$name]) {
            $provider = new $providerInfo['class']($providerInfo);
            $this->providers[$name] = $provider;
        }
        if (!$provider)
            throw new Exception('Auth confirm provider not found: ' . $name);
        return $provider;
    }
}


