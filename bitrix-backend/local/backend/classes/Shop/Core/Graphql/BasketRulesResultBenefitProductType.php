<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\EnumType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketRulesResultBenefitProductType extends ObjectType
{
    const NAME = 'BasketRulesResulBenefitProduct';

    public function getFieldsInfo()
    {
        return [
            'PRODUCT_ID' => Types::int(),
            'QUANTITY' => Types::int(),
            'TYPE' => Types::nonNull(new EnumType([
                'name' => static::NAME . 'Type',
                'values' => [
                    'productGift',
                    'productSpecial',
                ],
            ])),
        ];
    }
}
