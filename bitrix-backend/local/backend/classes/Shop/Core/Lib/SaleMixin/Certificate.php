<?php

namespace Shop\Core\Lib\SaleMixin;

use Main\Entity\IBlock\ElementQuery;
use Shop\Core\Entity\GiftCertificate;

class Certificate extends Base
{
    static function onOrderPaid($order, $basketItem)
    {
        $productElementId = $basketItem->getField('PRODUCT_ID');
        $quantity = $basketItem->getField('QUANTITY');

        $element = GiftCertificate::query()
            ->filter([
                'PROPERTY_ORDER_ID' => $order->getId(),
                'PROPERTY_PRODUCT_ID' => $basketItem->getField('PRODUCT_ID')
            ])
            ->first();

        if ($element) return;

        $productElement = ElementQuery::getComplexSingle($productElementId);

        if ($productElement) {

            $productElementPrice = $productElement->getPrice();

            for ($i = 0; $i < $quantity; $i++) {
                $certElement = GiftCertificate::create();
                $certElement->setFieldValue('NAME', $productElement['NAME']);
                $certElement->setPropValue('PRODUCT_ID', $productElementId);
                $certElement->setPropValue('SUM', $productElementPrice);
                $certElement->setPropValue('ORDER_ID', $order->getId());
                $certElement->setPropValue('BASKET_ITEM_ID', $basketItem->getId());
                $certElement->save();
            }
        }
    }
}
