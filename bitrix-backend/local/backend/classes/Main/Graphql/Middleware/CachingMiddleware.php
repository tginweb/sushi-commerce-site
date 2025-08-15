<?php

namespace Main\Graphql\Middleware;

use GraphQL\Executor\Promise\Promise;
use Main\DI\Containerable;

class CachingMiddleware
{
    use Containerable;

    public $defaultTtl = 3600;

    public function __construct()
    {
    }

    public function __invoke(callable $next): callable
    {
        return function ($root, $args, $context, $info) use ($next) {

            $fieldConfig = $info->fieldDefinition->config;

            // Пропускаем если кеширование отключено
            if (!($fieldConfig['cache'] ?? false)) {
                return $next($root, $args, $context, $info);
            }

            $cacheKey = $this->generateCacheKey($info, $args, $context);

            $item = $this->getCacheProvider()->getItem($cacheKey, $this->getTtl($fieldConfig));

            if ($item->isHit()) {
                die(json_encode($item->get()));
                return $item->get();
            }

            $result = $next($root, $args, $context, $info);

            if ($result instanceof Promise) {
                return $result->then(function ($value) use ($item, $fieldConfig) {
                    $this->saveToCache($item, $value, $fieldConfig);
                    return $value;
                });
            }

            $this->saveToCache($item, $result, $fieldConfig);
            return $result;
        };
    }

    private function generateCacheKey($info, $args, $context)
    {
        return join('_', [$info->parentType->name, $info->fieldName, md5(json_encode($args))]);
    }

    public function getCacheProvider()
    {
        return $this->container->getCacheService()->getProvider();
    }

    public function getTtl($fieldConfig = [])
    {
        return $fieldConfig['cacheTtl'] ?? $this->defaultTtl;
    }

    private function saveToCache($item, $value, $fieldConfig): void
    {
        $item->set($value)->expiresAfter($this->getTtl($fieldConfig));
        $this->getCacheProvider()->saveItem($item);
    }
}
