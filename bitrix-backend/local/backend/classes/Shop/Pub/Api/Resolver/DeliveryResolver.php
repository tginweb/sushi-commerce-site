<?php

namespace Shop\Pub\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Shop\Core\Entity\DeliveryZone;
use Shop\Core\Graphql\DeliveryZoneType;

class DeliveryResolver extends ResolversGenerator
{
    public $ns = 'sale_delivery_';

    function getQueryMap()
    {
        return [
            'zones' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(DeliveryZoneType::class)),
                    'resolve' => [$this, 'queryZones']
                ];
            },
        ];
    }

    function queryZones($rootValue, $args)
    {
        return DeliveryZone::query()
            ->setClientQuery($args)
            ->withViewList()
            ->getList();
    }
}



