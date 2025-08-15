<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class DiscountModeEnum extends BaseEnum
{
    static function options()
    {
        return [
            'percent' => 'Проценты',
            'fixed' => 'Фиксированная',
        ];
    }
}
