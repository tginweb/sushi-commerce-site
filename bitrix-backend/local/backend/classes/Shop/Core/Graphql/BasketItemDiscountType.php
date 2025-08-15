<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\EnumType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketItemDiscountType extends ObjectType
{
    const NAME = 'BasketItemDiscount';

    public function getFieldsInfo()
    {
        return [
            'RULE' => Types::string(),
            'TARGET' => Types::nonNull(new EnumType([
                'name' => static::NAME . 'Target',
                'values' => [
                    'basket',
                    'delivery',
                    'total',
                    'product',
                    'section',
                ],
            ])),
            'TYPE' => Types::nonNull(new EnumType([
                'name' => static::NAME . 'Type',
                'values' => [
                    'percent',
                    'fixed',
                ],
            ])),
            'AMOUNT' => Types::nonNull(Types::float()),
            'DISCOUNTED_PRICE' => Types::nonNull(Types::float()),
            'BASE_PRICE' => Types::nonNull(Types::float()),
        ];
    }
}



