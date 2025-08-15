<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;
use Main\Module;

/**
 * @method Module getModule()
 */
class WebSocketResolver extends ResolversGenerator
{
    public $ns = 'websocket_';

    function getMutationMap()
    {
        return [
            'ping' => function () {
                return [
                    'type' => Types::JSON(),
                    'resolve' => [$this, 'mutationWebsocketPing']
                ];
            }
        ];
    }

    function mutationWebsocketPing($rootValue, $args, $ctx, $info, Response $response)
    {
        $service = $this->container->getWebSocketService();
        $service->emitClientEventBySession(
            'ping',
            [
                'time' => time()
            ]
        );
        return true;
    }
}



