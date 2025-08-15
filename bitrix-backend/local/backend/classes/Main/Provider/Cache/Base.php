<?php

namespace Main\Provider\Cache;

use const TG\Main\Provider\Cache\SITE_ID;

abstract class Base
{
    public $cache;

    abstract public function get(string $key, callable $callback, float $beta = null, array &$metadata = null);

    abstract public function saveItem($item);

    abstract public function deleteItem($item);

    abstract public function invalidateTags($tags);

    function getGraphqlCacheItem($params, $path, $args)
    {
        $cacheKey = join('_', [...$path, '_', md5(json_encode($args))]);
        $cacheTtl = $params['ttl'] ?? 3600;
        $cacheTags = !empty($params['tags']) ? $this->prepareTags($params['tags']) : [];
        return $this->getItem($cacheKey, $cacheTtl, $cacheTags);
    }

    function prepareTags($tags)
    {
        $result = [];
        $contextVars = $this->getContextVars();

        foreach ($tags as $tag) {

            if (isset($contextVars[$tag])) {
                $ptag = $contextVars[$tag];
            } else {
                $ptag = $tag;
            }

            if (is_array($ptag)) {
                foreach ($ptag as $v) {
                    $result[] = $tag . '_' . $v;
                }
            } else {
                $result[] = $tag . '_' . $ptag;
            }

        }

        return $result;
    }

    function getContextVars()
    {
        if (!isset($this->contextVars)) {
            global $USER;
            $this->contextVars = [
                'USER_ID' => $USER->IsAuthorized() ? $USER->GetID() : 0,
                'USER_GROUP' => $USER->GetUserGroupArray(),
                'SITE_ID' => SITE_ID
            ];
        }
        return $this->contextVars;
    }

    abstract public function getItem(string $key, $ttl = null, $tags = []);

    function prepareArguments($args, $ctx)
    {
        $result = [];
        $contextVars = $this->getContextVars();

        foreach ($args as $key => $arg) {

            if (is_callable($arg)) {
                $result[$key] = $arg($ctx);
            } else {
                if (isset($contextVars[$arg])) {
                    if (is_numeric($key)) {
                        $key = $arg;
                    }
                    $arg = $contextVars[$arg];
                }

                if (is_array($arg)) {
                    $result[$key] = join(',', $arg);
                } else {
                    $result[$key] = $arg;
                }
            }
        }

        return $result;
    }

    function cacheCallback($key, $cb)
    {
        $cacheItem = $this->getItem($key);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        } else {
            $result = $cb();
            $cacheItem->set($result);
            $this->saveItem($cacheItem);
            return $result;
        }
    }
}



