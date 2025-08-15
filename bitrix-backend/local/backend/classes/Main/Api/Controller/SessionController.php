<?php

namespace Main\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;

class SessionController extends BaseController
{
    function __construct()
    {
        $this->getRouter()->map('GET', '/session/byClientId', [$this, 'querySessionByClientId']);
        $this->getRouter()->map('GET', '/session/state', [$this, 'queryState']);
    }

    function querySessionByClientId(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $appClient = $this->container->getAppClientService()->ensureAppClient(
            $params['clientId'],
            $params['clientToken'],
            false
        );
        return $appClient ? $appClient->getClientData() : null;
    }

    function queryState(ServerRequestInterface $request)
    {
        $sess = $this->container->getSession();

        return [
            'fuserId' => $sess->getFuserId(),
            'userId' => $sess->getUserId(),
            'sessionId' => $sess->getSessionId(),
        ];
    }
}



