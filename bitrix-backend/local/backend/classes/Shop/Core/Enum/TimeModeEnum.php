<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class TimeModeEnum extends BaseEnum
{
    const NEAREST = 'nearest';
    const CUSTOM = 'custom';

    public static function values()
    {
        return [static::NEAREST, static::CUSTOM];
    }

    public static function options()
    {
        return [
            static::NEAREST => 'ближайшее',
            static::CUSTOM => 'выбранное',
        ];
    }
}
