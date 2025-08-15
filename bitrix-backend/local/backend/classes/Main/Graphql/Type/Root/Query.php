<?php

namespace Main\Graphql\Type\Root;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Router;
use Main\Graphql\Type\ObjectType;

class Query extends ObjectType
{
    public function __construct(Router $router)
    {
        $queries = $router->getQueries();

        foreach ($queries as $name => $query) {
            $query['resolve'] = $router->createResolver($query['resolve']);
            $router->queries[$name] = $query;
        }

        $config = [
            'name' => 'Query',
            'fields' => $queries,
            'resolveField' => function ($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }
}
