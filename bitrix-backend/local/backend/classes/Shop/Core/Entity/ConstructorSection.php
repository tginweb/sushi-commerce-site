<?php

namespace Shop\Core\Entity;

class ConstructorSection extends ProductSection
{
    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product_constructor');
    }
}
