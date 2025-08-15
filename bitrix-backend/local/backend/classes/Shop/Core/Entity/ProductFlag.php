<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class ProductFlag extends ElementModel
{
    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product_flag');
    }
}
