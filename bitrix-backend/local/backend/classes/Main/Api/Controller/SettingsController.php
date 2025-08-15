<?php

namespace Main\Api\Controller;

use Illuminate\Http\Response;

class SettingsController extends BaseController
{
    function __construct()
    {
        $router = $this->container->getRouter();
        $router->get('settings.list', '/app/settings', [$this, 'queryList']);
    }

    function queryList($request, Response $response)
    {
        $response->setContent([
            'settings' => $this->container->getSettingsService()->getClientPublicSettings(),
        ]);
    }
}
