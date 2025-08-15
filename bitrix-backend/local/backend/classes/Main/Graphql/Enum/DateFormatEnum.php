<?php

namespace Main\Graphql\Enum;

class DateFormatEnum extends BaseEnum
{
    static function options()
    {
        return [
            'datetime' => 'Дата и время',
            'date' => 'Дата',
        ];
    }
}
