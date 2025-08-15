<?php

namespace Shop\Pub\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Graphql\OrderStatusType;

class SaleResolver extends ResolversGenerator
{
    public $ns = 'sale_';

    function getQueryMap()
    {
        return [
            'order_statuses' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::listOf(Types::getNonNull(OrderStatusType::class)),
                    'resolve' => [$this, 'queryOrderStatuses'],
                ];
            },
        ];
    }

    function queryOrderStatuses($rootValue, $args)
    {
        return array_values(OrderModel::statusesInfo());
    }
}



