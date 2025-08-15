<?php

namespace Main\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Generator\ResolversGenerator;

class Router
{
    public $queries = [];
    public $mutations = [];

    public $middlewares = [];
    public $resolversGenerators = [];

    function query($name, $info)
    {
        $this->queries[$name] = $info;
    }

    function queries($map)
    {
        foreach ($map as $name => $info) {
            $this->query($name, $info);
        }
    }

    function mutation($name, $info)
    {
        $this->mutations[$name] = $info;
    }

    function mutations($map)
    {
        foreach ($map as $name => $info) {
            $this->mutation($name, $info);
        }
    }

    function getQueries()
    {
        return $this->queries;
    }

    function getMutations()
    {
        return $this->mutations;
    }

    function useMiddleware($fn)
    {
        $this->middlewares[] = $fn;
    }

    function createResolver($resolver): callable
    {
        return function ($rootValue, $args, $context, ResolveInfo $info) use ($resolver) {
            if ($info->fieldDefinition->config['skipGlobalMiddleware'] ?? false) {
                return $resolver;
            }
            foreach ($this->middlewares as $middleware) {
                $resolver = $middleware($resolver);
            }
            return $resolver($rootValue, $args, $context, $info);
        };
    }

    function addResolversGenerator(...$items)
    {
        /* @var $item ResolversGenerator */
        foreach ($items as $item) {
            $this->resolversGenerators[$item->getNs()][] = $item;
        }
    }

    function registerResolversGenerator()
    {
        foreach ($this->resolversGenerators as $ns => $items) {
            /* @var $item ResolversGenerator */
            foreach ($items as $item) {
                $this->queries($item->generateQueries());
                $this->mutations($item->generateMutations());
            }
        }
    }
}
