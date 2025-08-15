<?php

namespace Shop\Core\Lib\SaleMixin;

use Bitrix\Main\EventResult;

class WithGift extends Base
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

        if ($inputProps) {

            $inputPropsValue = $inputProps['VALUE'];

            if ($inputPropsValue['GIFT']) {

                $productGifts = $product->getProp('GIFTS', 'VALUE', true);
                $productGiftsDesc = $product->getProp('GIFTS', 'DESCRIPTION', true);
                $giftIndex = array_search($inputPropsValue['GIFT'], $productGifts);

                if ($giftIndex !== false) {

                    $productGiftDesc = $productGiftsDesc[$giftIndex];

                    $basketItemProps['PROP.DESC'] = [
                        'NAME' => 'Подарок',
                        'VALUE' => 'Подарок: ' . $productGiftDesc,
                    ];

                    $basketItemProps['PROP.GIFT'] = [
                        'NAME' => 'Подарок',
                        'VALUE' => $inputPropsValue['GIFT'],
                    ];

                    $basketItemProps['PROP.GIFT_NAME'] = [
                        'NAME' => 'Подарок',
                        'VALUE' => $productGiftDesc,
                    ];

                } else {
                    return new EventResult(EventResult::ERROR);
                }
            }
        }
    }
}
