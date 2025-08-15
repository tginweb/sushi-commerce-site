<?php

namespace Push\Pub;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;
use Push\Core\Service\PushService;
use Push\Pub\Api\Resolver\PushResolver;
use TG\Push\Pub\Graphql;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        $this->container->define(PushService::DI_SERVICE, PushService::class);

        parent::register();
    }

    function registerTypes()
    {
        Types::types([
            'PushSubscriber' => Graphql\PushSubscriber::class,
        ]);
    }

    function registerApiResolvers()
    {
        new PushResolver();
    }
}
