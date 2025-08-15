<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ProductBenefitType extends ObjectType
{
    const NAME = 'ProductBenefit';

    public function getFieldsInfo()
    {
        return [
            'PRODUCT' => Types::getType('ProductElement'),
            'PRODUCT_ID' => Types::nonNull(Types::int()),
            'QUANTITY' => Types::nonNull(Types::int()),
            'IS_GIFT' => Types::boolean(),
        ];
    }
}
