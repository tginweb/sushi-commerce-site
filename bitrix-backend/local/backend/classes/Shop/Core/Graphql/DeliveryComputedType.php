<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class DeliveryComputedType extends ObjectType
{
    const NAME = 'DeliveryComputed';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::nonNull(Types::int()),
            'NAME' => Types::string(),
            'SERVICE' => Types::get(DeliveryServiceType::class),
            'CALC_TIMESTAMP' => Types::int(),
            'PRICE' => Types::float(),
            'PRICE_FORMATED' => Types::string(),
            'DELIVERY_DISCOUNT_PRICE' => Types::string(),
            'DELIVERY_DISCOUNT_PRICE_FORMATED' => Types::string(),
            'PERIOD_TEXT' => Types::string(),
            'CALCULATE_ERRORS' => Types::JSON(),
            'CALCULATE_DESCRIPTION' => Types::JSON(),
        ];
    }

    public function resolve_SERVICE($parent, $args, $ctx)
    {
        return \Bitrix\Sale\Delivery\Services\Manager::getById($parent['ID']);
    }
}
