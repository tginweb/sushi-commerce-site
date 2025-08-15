<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketRuleActionBenefitProductType extends ObjectType
{
    const NAME = 'BasketRuleActionProduct';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'PRODUCT_ID' => Types::nonNull(Types::int()),
            'MAX' => Types::int(),
        ];
    }
}
