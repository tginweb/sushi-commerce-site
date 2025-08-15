<?php

namespace Geo\Pub;

use Bitrix;
use Geo\Pub\Api\Resolver;
use Main\Lib\Common\BaseModule as BaseModule;

class Module extends BaseModule
{
    function __construct()
    {

    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();
        $router->addResolversGenerator(
            Resolver::create()->addAllQueries()
        );
    }
}



