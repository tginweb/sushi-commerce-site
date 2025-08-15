<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class DiscountTargetEnum extends BaseEnum
{
    public static function options()
    {
        return [
            'product' => 'на товары',
            'basket' => 'на корзину',
            'delivery' => 'на доставку',
            'total' => 'общая',
        ];
    }
}
