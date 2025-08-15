<?php

namespace Shop\Core\Entity;

class Constructor extends Product
{
    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product_constructor');
    }
}
