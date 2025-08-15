<?php

namespace Telegram\Core;

use Main\Lib\Common\BaseComponent;

class Module extends BaseComponent
{
    function register($scopes = [])
    {
        $container = $this->container;

        $container->define(Service::class);

        parent::register();
    }

    function registerTypes()
    {
        $gql = $this->container->getRegistryService();

        //$gql->type('TelegramEventConnection', EventConnectionType::class);
        //$gql->type('TelegramEventFilter', EventFilterType::class);
    }
}



