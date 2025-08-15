<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ProductPriceType extends ObjectType
{
    const NAME = 'ProductPrice';

    public function getFieldsInfo()
    {
        return [
            'PRICE' => Types::nonNull(Types::float()),
            'DISCOUNTED' => Types::nonNull(Types::float()),
            'DISCOUNT_PERCENT' => Types::nonNull(Types::float()),
        ];
    }

    public function resolve_PRICE($parent, $args, $ctx)
    {
        return $parent['PRICE'] ?: 0;
    }

    public function resolve_DISCOUNTED($parent, $args, $ctx)
    {
        return $parent['DISCOUNTED'] ?: 0;
    }

    public function resolve_DISCOUNT_PERCENT($parent, $args, $ctx)
    {
        return $parent['DISCOUNT_PERCENT'] ?: 0;
    }
}
