<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;

class AppResolver extends ResolversGenerator
{
    public $ns = 'app_';

    function getQueryMap()
    {
        return [
            'state' => function () {
                return [
                    'type' => Types::JSON(),
                    'resolve' => [$this, 'queryAppState']
                ];
            }
        ];
    }

    function queryAppState($rootValue, $args)
    {
        $env = $this->container->getEnvService();
        $data = [
            'ENV' => $env->getEnvName(),
            'SESSION_ID' => session_id(),
            'COOKIES' => $_COOKIE
        ];
        return $data;
    }
}



