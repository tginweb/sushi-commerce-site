<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class DeliveryTransportTypeEnum extends BaseEnum
{
    const COURIER = 'courier';
    const PICKUP = 'pickup';

    public static function values()
    {
        return [static::COURIER, static::PICKUP];
    }

    public static function options()
    {
        return [
            static::COURIER => 'доставка курьером',
            static::PICKUP => 'самовывоз',
        ];
    }
}
