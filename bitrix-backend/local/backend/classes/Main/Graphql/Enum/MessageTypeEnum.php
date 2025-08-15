<?php

namespace Main\Graphql\Enum;

class MessageTypeEnum extends BaseEnum
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
