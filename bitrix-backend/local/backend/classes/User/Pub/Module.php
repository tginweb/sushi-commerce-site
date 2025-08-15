<?php

namespace User\Pub;

use Main\Lib\Common\BaseModule as BaseModule;

class Module extends BaseModule
{
    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            \User\Pub\Api\AuthResolver::create()->addAll(),
            \User\Pub\Api\UserResolver::create()->addAll(),
        );
    }

    function registerApiControllers()
    {
        new \User\Pub\Api\Controller();
    }
}



