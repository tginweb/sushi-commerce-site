<?php

namespace Main\Provider\Cache;

use Main\DI\Containerable;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

class Redis extends Base
{
    use Containerable;

    public $redis;
    public $cache;

    function __construct($conf)
    {
        $this->redis = $this->container->getRedisService()->getConnection($conf['connection']);
        $this->cache = new RedisTagAwareAdapter($this->redis, $namespace = '', $defaultLifetime = 0);
    }

    function get(string $key, callable $callback, float $beta = null, array &$metadata = null)
    {
        return $this->cache->get($key, $callback, $beta = null, $metadata);
    }

    function saveItem($item)
    {
        $r = $this->cache->save($item);
    }

    function invalidateTags($tags)
    {
        return $this->cache->invalidateTags($tags);
    }

    function getItem(string $key, $ttl = null, $tags = [])
    {
        $key = 'cache.' . $key;

        $item = $this->cache->getItem($key);

        if ($ttl)
            $item->expiresAfter($ttl);

        if (!empty($tags)) {
            $item->tag($tags);
        }

        return $item;
    }

    function clearAll()
    {
        $this->redis->del($this->redis->keys('*'));
    }

    function deleteItem($item)
    {
        return $this->cache->deleteItem($item);
    }
}



