<?php

namespace Main\Graphql\Middleware;

use GraphQL\Type\Schema;

class GlobalMiddlewareSchema extends Schema
{
    private array $middlewares = [];

    public function __construct(array $config, array $middlewares = [])
    {
        parent::__construct($config);
        $this->middlewares = $middlewares;
    }

    public function getFieldResolver($root, $args, $context, $info): callable
    {
        $resolver = parent::getFieldResolver($root, $args, $context, $info);

        // Пропускаем поля с флагом skipGlobalMiddleware
        if ($info->fieldDefinition->config['skipGlobalMiddleware'] ?? false) {
            return $resolver;
        }

        // Применяем все глобальные middleware
        foreach (array_reverse($this->middlewares) as $middleware) {
            $resolver = $middleware($resolver);
        }

        return $resolver;
    }
}
