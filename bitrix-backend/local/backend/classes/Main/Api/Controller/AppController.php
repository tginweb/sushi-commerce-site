<?php

namespace Main\Api\Controller;

use Illuminate\Http\Response;

class AppController extends BaseController
{
    function __construct()
    {
        $router = $this->container->getRouterService()->getNewRouter();

        $router->get('server.restart', '/server/restart', [$this, 'mutationServerRestart']);
    }

    function mutationServerRestart($request, Response $response)
    {
        $response->setContent([
            'success' => true,
        ]);
    }
}
