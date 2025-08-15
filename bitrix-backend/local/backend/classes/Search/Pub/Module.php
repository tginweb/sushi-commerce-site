<?php

namespace Search\Pub;

use Main\Lib\Common\BaseModule;
use Search\Pub\Api\Resolver;

class Module extends BaseModule
{
    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            Resolver::create()->addAllQueries()
        );
    }
}
