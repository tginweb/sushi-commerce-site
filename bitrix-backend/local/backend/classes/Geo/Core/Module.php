<?php

namespace Geo\Core;

use Bitrix;
use Main\Graphql\Types;
use Main\Lib\Common\BaseModule as BaseModule;

class Module extends BaseModule
{
    function __construct()
    {
        $this->container->register(Service::class);
    }

    function registerTypes()
    {
        Types::type('GeoObject', \Geo\Core\Graphql\GeoObjectType::class);
    }
}



