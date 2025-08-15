<?php

namespace Main\Api\Controller;

use Illuminate\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class EntityController extends BaseController
{
    function __construct()
    {
        $router = $this->getRouter();

        $router->map('POST', '/entity/types-info', [$this, 'getEntityTypesInfo']);
    }

    function getEntityTypesInfo(ServerRequestInterface $request, Response $response)
    {

    }
}
