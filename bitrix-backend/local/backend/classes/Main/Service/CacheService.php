<?php

namespace Main\Service;

use Bitrix\Main\Web\HttpClient;
use Exception;
use Main\Provider\Cache\Base;
use TG\Main\Helper;

class CacheService extends BaseService
{
    public $cache;
    public $contextVars = null;
    public $redis;
    public $config = [];
    public $providers = [];

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('CACHE');
    }

    function clearAll()
    {
        $server = \Main\Helper\Http::requestServer();

        $httpClient = new HttpClient();

        $httpClient->get($server . '/ssr-cache-clear/');

        foreach ($this->config['PROVIDERS'] as $pname => $pdata) {
            $provider = $this->getProvider($pname);
            $provider->clearAll();
        }

        $this->container->doAction('cache.clear');
    }

    /**
     * @return Base
     */
    function getProvider($name = 'default')
    {

        if ($this->providers[$name]) {
            $provider = $this->providers[$name];
        } else if ($providerInfo = $this->config['PROVIDERS'][$name]) {
            $provider = $this->providers[$name] = new $providerInfo['class']($providerInfo);
        }

        if (!$provider)
            throw new Exception('Cacher not found');

        return $provider;
    }

    function getCachedCallback($key, $cb, $args = [], $ttl = null, $refetch = false)
    {
        $cid = $key;

        if (!empty($args))
            $cid .= '.' . md5(json_encode($args));

        $cid = preg_replace('/[\.\:]/', '_', $cid);

        if ($refetch)
            $this->container->getCacheService()->getProvider()->deleteItem($cid);

        return $this->container->getCacheService()->getProvider()->get($cid, function ($item) use ($cb, $args, $ttl) {

            if ($ttl)
                $item->expiresAfter($ttl);

            $args[] = $item;

            return call_user_func_array($cb, $args);
        });
    }
}
