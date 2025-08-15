<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketRulesResultType extends ObjectType
{
    const NAME = 'BasketRulesResult';

    public function getFieldsInfo()
    {
        return [
            'DISCOUNTS_BASKET' => Types::nonNullListOf(Types::getNonNull(BasketRuleActionDiscountType::class)),
            'DISCOUNTS_DELIVERY' => Types::nonNullListOf(Types::getNonNull(BasketRuleActionDiscountType::class)),
            'DISCOUNTS_TOTAL' => Types::nonNullListOf(Types::getNonNull(BasketRuleActionDiscountType::class)),
            'DISCOUNTS_PRODUCT' => Types::nonNullListOf(Types::getNonNull(BasketRuleActionDiscountType::class)),
            'DISCOUNTS_SECTION' => Types::nonNullListOf(Types::getNonNull(BasketRuleActionDiscountType::class)),

            'BENEFIT_PRODUCTS' => Types::nonNullListOf(Types::getNonNull(BasketRulesResultBenefitProductType::class)),

            'ALLOW' => Types::nonNullListOf(Types::string()),
            'DENY' => Types::nonNullListOf(Types::string()),
        ];
    }
}
