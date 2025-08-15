<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class BasketRuleActionTypeEnum extends BaseEnum
{
    public static function options()
    {
        return [
            'discount' => 'скидка',
            'gift' => 'подарок',
        ];
    }
}
