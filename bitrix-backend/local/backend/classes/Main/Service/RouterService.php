<?php

namespace Main\Service;


use Aura\Router\RouterContainer;


class RouterService extends BaseService
{
    public $router;
    public $routerContainer;

    function getRouterContainer()
    {
        if (!$this->routerContainer) {
            $this->routerContainer = new RouterContainer();
        }
        return $this->routerContainer;
    }

    function getRouter()
    {
        if (!$this->router) {
            $this->router = $this->getRouterContainer()->getMap();
        }
        return $this->router;
    }
}


