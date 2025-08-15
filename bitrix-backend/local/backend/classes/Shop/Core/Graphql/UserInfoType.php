<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserInfoType extends ObjectType
{
    const NAME = 'SaleUserInfo';

    public function getFieldsInfo()
    {
        return [
            'ORDERS_COUNT' => Types::int(),
            'CLIENT_CARD' => Types::get(ClientCardType::class),
        ];
    }

    public function resolve_ORDERS_COUNT($parent, $args, $ctx)
    {
        return $this->container->getOrderService()->getUserOrdersCountCached($this->container->getUserId());
    }

    public function resolve_CLIENT_CARD($parent, $args, $ctx)
    {
        return $this->container->getSaleClientCardService()->getCurrentUserCard();
    }

}
