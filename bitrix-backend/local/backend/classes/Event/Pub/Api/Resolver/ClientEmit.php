<?php

namespace Event\Pub\Api\Resolver;

use Event\Core\Graphql\ClientEmitType;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;

class ClientEmit extends ResolversGenerator
{
    const NAMESPACE = 'event_pub_';

    function getQueryMap()
    {
        return [
            'list' => function () {
                return [
                    'type' => Types::listOf(Types::get(ClientEmitType::class)),
                    'resolve' => [$this, 'queryList']
                ];
            }
        ];
    }

    function queryList($rootValue, $args)
    {
        return [];
    }
}



