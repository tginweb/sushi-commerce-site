<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class DeliveryCalcStatusEnum extends BaseEnum
{
    static function options()
    {
        return [
            'success' => 'Успешно',
            'error' => 'Ошибка',
            'info' => 'Информация',
            'warning' => 'Внимание',
        ];
    }
}
