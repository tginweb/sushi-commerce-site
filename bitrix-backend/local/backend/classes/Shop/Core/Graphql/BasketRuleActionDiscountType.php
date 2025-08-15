<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Types;
use Shop\Core\Enum\DiscountModeEnum;
use Shop\Core\Enum\DiscountTargetEnum;

class BasketRuleActionDiscountType extends BasketRuleActionType
{
    const NAME = 'BasketRuleActionDiscount';

    public function __construct($config = [])
    {
        $config += [
            'interfaces' => [Types::getType('BasketRuleActionInterface', true)],
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'TARGET' => Types::nonNull(Types::get(DiscountTargetEnum::class)),
                'PRODUCT_IDS' => Types::nonNullListOf(Types::nonNull(Types::int())),
                'SECTION_IDS' => Types::nonNullListOf(Types::nonNull(Types::int())),
                'AMOUNT' => Types::nonNull(Types::int()),
                'AMOUNT_SURCHARGE' => Types::nonNull(Types::int()),
                'MODE' => Types::nonNull(Types::get(DiscountModeEnum::class)),
            ];
    }
}
