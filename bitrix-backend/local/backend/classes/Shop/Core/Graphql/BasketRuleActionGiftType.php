<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Types;

class BasketRuleActionGiftType extends BasketRuleActionType
{
    const NAME = 'BasketRuleActionGift';

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
                'GIFT_IDS' => Types::nonNullListOf(Types::nonNull(Types::int())),
            ];
    }
}
