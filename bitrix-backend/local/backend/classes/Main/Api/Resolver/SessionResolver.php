<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;

class SessionResolver extends ResolversGenerator
{
    public $ns = 'session_';

    function getQueryMap()
    {
        return [
            'state' => function () {
                return [
                    'type' => Types::JSON(),
                    'resolve' => [$this, 'querySessionState']
                ];
            }
        ];
    }

    function querySessionState($rootValue, $args)
    {
        $sess = $this->container->getSession();
        return [
            'fuserId' => $sess->getFuserId(),
            'sessionId' => $sess->getSessionId()
        ];
    }
}



