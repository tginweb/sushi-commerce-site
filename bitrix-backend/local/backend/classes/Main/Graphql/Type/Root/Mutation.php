<?php

namespace Main\Graphql\Type\Root;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Router;
use Main\Graphql\Type\ObjectType;

class Mutation extends ObjectType
{
    public function __construct(Router $router)
    {
        $mutations = $router->getMutations();

        $config = [
            'name' => 'Mutation',
            'fields' => $mutations,
            'resolveField' => function ($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }
}
