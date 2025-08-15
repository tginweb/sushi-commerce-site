<?php

namespace Menu\Pub;

use Main\Lib\Common\BaseModule as BaseModule;
use Menu\Pub\Api\Resolver;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);

        $this->container->addFilter('menu:menus', [$this, 'buildMenu']);
    }

    function buildMenu($menus)
    {
        return $this->container->getMenuService()->fillMenusFromIblock($menus, 47);
    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            Resolver::create()->addAllQueries()
        );
    }
}



