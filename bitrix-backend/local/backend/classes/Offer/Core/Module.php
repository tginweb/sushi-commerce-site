<?php

namespace Offer\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);
        $container = $this->container;
        $container->define(Service::class);
    }

    function getService()
    {
        return $this->container->get(Service::class);
    }

    function registerTypes()
    {
        Types::types([
            'Offer' => \Offer\Core\Graphql\OfferType::class,
            'OfferSlide' => \Offer\Core\Graphql\OfferSlideType::class,
        ]);
    }
}
