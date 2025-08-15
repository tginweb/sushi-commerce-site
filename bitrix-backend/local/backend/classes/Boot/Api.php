<?php

namespace Boot;

use Main\DI\Containerable;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

class Api
{
    use Containerable;

    public $requestCacheItem;
    public $cacheAdapter;

    function getCacheAdapter()
    {
        if (!$this->cacheAdapter) {
            $redis = RedisAdapter::createConnection('redis://localhost:6379/0');
            $this->cacheAdapter = new RedisTagAwareAdapter($redis, '', 0);
        }
        return $this->cacheAdapter;
    }

    function getCachedResponse()
    {
        return null;
    }

    function exitFlushCached()
    {
        $cachedResponse = $this->getCachedResponse();
        if ($cachedResponse) {
            print $cachedResponse;
            exit();
        }
    }
}



