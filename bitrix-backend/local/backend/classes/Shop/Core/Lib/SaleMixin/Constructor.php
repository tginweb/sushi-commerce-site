<?php

namespace Shop\Core\Lib\SaleMixin;

class Constructor extends Base
{
    static function OnSaleBasketBeforeSavedItem($event)
    {
        $product = $event->getParameter('PRODUCT');
        $basketItem = $event->getParameter('ITEM');
        $basketItemProps = $event->getParameter('PROPS');

        if (!$basketItem->isNewItem())
            return;

        $basketItemPropsCollection = $basketItem->getPropertyCollection();
        $basketItemPropsValues = $basketItemPropsCollection->getPropertyValues();
        $basket = $basketItem->getBasket();

        $inputProps = $basketItemPropsValues['INPUT_PROPS'];

        $constructorBuildIblockId = static::container()->getIblockService()->getIBlockIdByRole('product_constructor_build');

        if ($product['IBLOCK_ID'] == $constructorBuildIblockId) {

            if (method_exists($product, 'fillBasketItem')) {
                $product->fillBasketItem($basketItem, $basketItemProps);
            }
        }

    }
}
