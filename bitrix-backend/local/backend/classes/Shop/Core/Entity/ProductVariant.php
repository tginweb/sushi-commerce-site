<?php

namespace Shop\Core\Entity;

class ProductVariant extends Product
{
    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product_offer');
    }

    public static function query($iblockId = null)
    {
        return new ProductVariantQuery(static::instantiateObject(), get_called_class(), $iblockId, ProductCollection::class);
    }

}
