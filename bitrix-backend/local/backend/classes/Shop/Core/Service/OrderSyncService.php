<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale\Payment;
use Company\Core\Entity\Office;
use Main\Service\BaseService;
use Shop\Core\Entity\BasketItem;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Entity\Product;

class OrderSyncService extends BaseService
{
    function setStatusFromService(OrderModel $order, $serviceStatus)
    {
        $newStatus = null;
        $isCanceled = null;
        $changed = false;

        switch ($serviceStatus) {
            case 'На кухне':
                $newStatus = 'K';
                break;
            case 'Принят':
                if ($order->getPropVal('IS_ACCEPTED') !== 'Y') {
                    $order->setPropVal('IS_ACCEPTED', 'Y');
                    $changed = true;
                }
                break;
            case 'У водителя':
                $newStatus = 'D';
                break;
            case 'Отмена':
                $isCanceled = true;
                break;
            case 'У координатора':
                if (method_exists($order, 'isSelfPickup') && $order->isSelfPickup()) {
                    $newStatus = 'D';
                }
                break;
            case 'Закрыт':
            case 'Исполнен':
                $newStatus = 'F';
                break;
        }

        if ($newStatus || isset($isCanceled)) {
            if (isset($isCanceled)) {
                if ($isCanceled) {
                    $order->cancelForce();
                    $changed = true;
                }
            }
            if ($newStatus) {
                if ($order->getField('STATUS_ID') !== $newStatus) {
                    $order->setField('STATUS_ID', $newStatus);
                    $changed = true;
                }
            }
        }

        return $changed;
    }

    function cancelForce(OrderModel $order)
    {
        /** @var Payment $payment */
        foreach ($order->getPaymentCollection() as $payment) {
            if ($payment->isPaid()) {
                $payment->setReturn("Y");
                $payment->save();
            }
        }
        $order->setField('CANCELED', 'Y');
    }

    function setDeliveryFromService(OrderModel $order, $serviceOrder)
    {
        $sposob = $serviceOrder['SposobDost'];

        $changed = false;

        switch ($sposob) {
            case 'Доставка':
                $newDeliveryId = 1;
                break;
            case 'Самовывоз':
                $newDeliveryId = 2;
                break;
        }

        if ($newDeliveryId && $newDeliveryId != $order->getDeliveryId()) {
            $order->setFieldNoDemand('DELIVERY_ID', $newDeliveryId);
            $order->createOrderShipment($newDeliveryId);
            $shipments = $order->getShipmentCollection();
            $shipments->save();
            $changed = true;
        }

        if ($serviceOrder['Podrazdel_ID']) {
            $office = Office::query()->filter([
                'PROPERTY_SERVICE_ID' => $serviceOrder['Podrazdel_ID']
            ])->withViewList()->first();
            if ($office) {
                if ($order->isSelfPickup()) {
                    if ($office['ID'] != $order->getPropVal('PICKUP_DEPARTMENT')) {
                        $order->setPropVal('PICKUP_DEPARTMENT', $office['ID']);
                        $changed = true;
                        $set = true;
                    }
                } else {
                    if ($office['ID'] != $order->getPropVal('DELIVERY_DEPARTMENT')) {
                        $order->setPropVal('DELIVERY_DEPARTMENT', $office['ID']);
                        $changed = true;
                        $set = true;
                    }
                }
            }
            /*
            file_put_contents(__DIR__ . '/delivery.json', json_encode([
                'Podrazdel_ID' => $serviceOrder['Podrazdel_ID'],
                'office' => $office,
                'set' => $set
            ], JSON_PRETTY_PRINT));
            */
        }


        return $changed;
    }

    function setPaymentFromService(OrderModel $order, $sposob)
    {
        $changed = false;

        if (preg_match('/Онлайн/i', $sposob)) {
            $newPaymentType = 'online';
        } else if (preg_match('/Безналичные/i', $sposob)) {
            $newPaymentType = 'card';
            $newPaymentId = 2;
        } else {
            $newPaymentType = 'cash';
            $newPaymentId = 1;
        }

        if ($newPaymentType && ($newPaymentType !== $order->getPropVal('PAYMENT_TYPE', 'cash'))) {
            $changed = true;
            if ($newPaymentType === 'online') {
                $order->ensureUnpaidOnlinePayment();
            }
            $order->setPropVal('PAYMENT_TYPE', $newPaymentType);
        }

        return $changed;
    }

    function setBasketFromService(OrderModel $order, $sostav)
    {
        $currencyCode = CurrencyManager::getBaseCurrency();

        $changed = false;

        $basket = $order->getBasket();
        $basketItems = [];

        /** @var BasketItem $basketItem */
        foreach ($basket as $basketItem) {
            $basketItems[$basketItem->getProductId()] = $basketItem;
        }

        $notFoundItems = [];
        $createItems = [];
        $updateItems = [];

        // skidka

        foreach ($sostav as $sostavItem) {

            $productId = intval($sostavItem['GoodsCode']);
            $quantity = intval($sostavItem['Quantity']);

            if (!$productId || !$quantity) continue;

            /** @var Product $product */
            $product = Product::query()->filter([
                'PROPERTY_SERVICE_ID' => $sostavItem['GoodsCode']
            ])->withViewList()->first();

            if (!$product) {
                $notFoundItems[] = $sostavItem;
                continue;
            }

            $basketItem = $basketItems[$product['ID']];

            if ($basketItem) {

                $updateItems[] = $sostavItem;

                if ($basketItem->getField('QUANTITY') != $quantity) {
                    $changed = true;
                    $basketItem->setField('QUANTITY', $quantity);
                }
                unset($basketItems[$product['ID']]);
            } else {

                $createItems[] = $sostavItem;

                $changed = true;

                $item = $basket->createItem('catalog', $product['ID']);

                $item->setFields([
                    'DELAY' => 'N',
                    'PRODUCT_ID' => $product['ID'],
                    'QUANTITY' => $quantity,
                    'NAME' => $product['NAME'],
                    'PRICE' => $product->getPrice(),
                    'CURRENCY' => $currencyCode,
                    'LID' => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => \Project\Sale\Core\Lib\CatalogProvider\Simple::class,
                    'CATALOG_XML_ID' => $product['IBLOCK_EXTERNAL_ID'],
                    'PRODUCT_XML_ID' => $product['EXTERNAL_ID']
                ]);

                $basketProps = [];

                if (!empty($product['IBLOCK_EXTERNAL_ID']))
                    $basketProps['CATALOG.XML_ID'] = [
                        'NAME' => 'Catalog XML_ID',
                        'VALUE' => $product['IBLOCK_EXTERNAL_ID'],
                        'SORT' => 100
                    ];

                if (!empty($product['EXTERNAL_ID']))
                    $basketProps['PRODUCT.XML_ID'] = [
                        'NAME' => 'Product XML_ID',
                        'VALUE' => $product['EXTERNAL_ID'],
                        'SORT' => 100
                    ];

                $propsRes = [];
                foreach ($basketProps as $code => $basketProp) {
                    $propsRes[] = $basketProp + [
                            'CODE' => $code,
                            'NAME' => $code,
                        ];
                }

                $propCollection = $item->getPropertyCollection();
                $propCollection->setProperty($propsRes);
            }
        }

        foreach ($basketItems as $productId => $item) {
            $item->delete();
            $changed = true;
        }

        if ($changed) {
            $basket->save();
        }


        return $changed;
    }

    function serviceOrderSync(OrderModel $order, $serviceOrder)
    {
        $changed = false;

        if (!empty($serviceOrder['History'])) {
            $lastStatus = end($serviceOrder['History']);
            if ($lastStatus['Status']) {
                if ($this->setStatusFromService($order, $lastStatus['Status']))
                    $changed = true;
            }
        }

        if (!empty($serviceOrder['DateDost'])) {
            $deliveryTimestamp = strtotime($serviceOrder['DateDost']);
            $deliveryTime = $deliveryTimestamp . ' ' . $deliveryTimestamp;
            if ($order->getDeliveryDateTime() !== $deliveryTime) {
                $order->setPropValue('CODE', 'DATE', date('d.m.Y', $deliveryTimestamp));
                $order->setPropValue('CODE', 'TIME', date('H:i', $deliveryTimestamp));
                $changed = true;
            }
        }

        if (!empty($serviceOrder['SposobDost'])) {
            if ($this->setDeliveryFromService($order, $serviceOrder))
                $changed = true;
        }

        if (!$order->isSelfPickup()) {
            $newCity = trim($serviceOrder['Gorod']);
            $newAddress = trim($serviceOrder['adress']);
            $newStreet = trim($serviceOrder['ulicha']);
            $newHouse = trim($serviceOrder['dom']);
            $newFlat = trim($serviceOrder['kvartirs']);

            $oldAddress = trim($order->getPropVal('ADDRESS'));
            $oldHouse = trim($order->getPropVal('HOUSE'));
            $oldFlat = trim($order->getPropVal('FLAT'));

            if ($newAddress) {
                if ($newHouse !== $oldHouse || $newFlat !== $oldFlat) {
                    $order->setPropVal('ADDRESS', join(', ', array_filter([$newStreet, $newHouse])));
                    $order->setPropVal('CITY', $newCity);
                    $order->setPropVal('SETTLEMENT', $newCity);
                    $order->setPropVal('STREET_PATH', $newStreet);
                    $order->setPropVal('HOUSE', $newHouse);
                    $order->setPropVal('FLAT', $newFlat);
                    $changed = true;
                }
            }
        }

        if (!empty($serviceOrder['Sostav'])) {
            if ($this->setBasketFromService($order, $serviceOrder['Sostav'])) $changed = true;
        }

        if (!empty($serviceOrder['OplataBonus'])) {
            $bonusesNew = intval($serviceOrder['OplataBonus']);
            if ($order->getBonuses() !== $bonusesNew) {
                $changed = true;
                $order->setPropVal('BONUSES', $bonusesNew);
            }
        }

        if (!empty($serviceOrder['Oplata'])) {
            $lastOplata = end($serviceOrder['Oplata']);
            if ($this->setPaymentFromService($order, $lastOplata['TipOplata']))
                $changed = true;
        }

        $prevBenefitType = $order->getBenefitType();

        $oldBonuses = $order->getBonuses();

        $newBonuses = intval($serviceOrder['OplataBonus']);
        $newDiscount = intval($serviceOrder['skidka']);
        $newPromocode = $serviceOrder['Promokod'];

        $discountChanged = false;

        if ($newPromocode) {

            $benefitType = 'promocode';

            $coupon = $order->getCoupon();

            if (!$coupon || !$coupon->matchCode($newPromocode)) {
                $order->couponApply($newPromocode);
                $changed = true;
            }

            if ($order->getBonuses()) {
                $order->setPropVal('BONUSES', 0);
                $changed = true;
            }

            if ($order->getPropVal('DISCOUNT_PERCENT')) {
                $order->setPropVal('DISCOUNT_PERCENT', 0);
                $order->setPropVal('DISCOUNT_REASON', '');
                $changed = true;
            }

        } else if ($newBonuses) {

            $benefitType = 'bonus';

            if ($order->isCouponsUsed()) {
                $order->couponsDelete();
                $changed = true;
            }

            if ($newBonuses != $oldBonuses) {
                $order->setPropVal('BONUSES', $newBonuses);
                $changed = true;
            }

            if ($order->getPropVal('DISCOUNT_PERCENT')) {
                $order->setPropVal('DISCOUNT_PERCENT', 0);
                $order->setPropVal('DISCOUNT_REASON', '');
                $changed = true;
            }

        } else if ($order->isSelfPickup()) {

            $benefitType = 'discount';

            if ($order->isCouponsUsed()) {
                $order->couponsDelete();
                $changed = true;
            }

            if ($order->getBonuses()) {
                $order->setPropVal('BONUSES', 0);
                $changed = true;
            }
        }

        if ($prevBenefitType !== $benefitType) {

            $order->setBenefitType($benefitType);

            if ($benefitType === 'discount') {
                $order->calcDiscountProps(true);
            }

            $discount = $order->getDiscount();
            \Bitrix\Sale\DiscountCouponsManager::clearApply(true);
            \Bitrix\Sale\DiscountCouponsManager::useSavedCouponsForApply(true);
            $discount->setOrderRefresh(true);
            $discount->setApplyResult(array());
            $order->getBasket()->refreshData(array('PRICE', 'COUPONS'));
            $discount->calculate();

            $changed = true;
        }

        return $changed;
    }
}



