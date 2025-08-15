<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Sale\Basket;
use Main\Service\BaseService;
use Shop\Core\Entity\OrderModel;
use const TG\Shop\Core\Service\SITE_ID;

class OrderHistoryService extends BaseService
{
    function getOrdersHistoryByPhone($phone, $filter, $nav = [])
    {
        $service = $this->container->get1СService();

        $serviceParams = [
            'phone' => $phone
        ];

        if ($filter['filter']['MODE'] === 'active') {
            $serviceParams['activeOnly'] = true;
        }

        $serviceOrders = $service->apiOrdersHistory($serviceParams, true);

        $orders = [];
        $currencyCode = CurrencyManager::getBaseCurrency();

        $index = 0;
        $basketItemIndex = 1;
        $limit = $nav['limit'] ?: 15;

        //$limit = 1;

        $goodsCodes = [];
        $productsByServiceId = [];

        foreach ($serviceOrders as $serviceOrder) {
            foreach ($serviceOrder['Sostav'] as $oitem) {
                $goodsCodes[$oitem['GoodsCode']] = $oitem['GoodsCode'];

                if ($oitem['Constructor'] && in_array($oitem['GoodsCode'], [43816])) {
                    $goodsCodes[$oitem['GoodsCode']] = $oitem['GoodsCode'];
                    $buildLines = explode(';', $oitem['Constructor']);
                    foreach ($buildLines as $buildLine) {
                        if (!$buildLine)
                            continue;
                        list($buildLineName, $buildLineInfo) = explode(':', $buildLine);
                        list($buildLineId, $buildLineInfoRight) = explode('=', $buildLineInfo);
                        $buildLineId = intval($buildLineId);
                        if ($buildLineId) {
                            $goodsCodes[$buildLineId] = $buildLineId;
                        }
                    }
                }
            }
        }

        $catalogIblockIds = [1, 24];

        foreach ($catalogIblockIds as $iblockId) {
            $res = \CIBlockElement::GetList(array(),
                [
                    'IBLOCK_ID' => $iblockId,
                    'ACTIVE' => 'Y',
                    'PROPERTY_SERVICE_ID' => $goodsCodes
                ],
                false,
                false,
                ["ID", "IBLOCK_ID", "NAME", 'PROPERTY_SERVICE_ID']
            );
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $productsByServiceId[$arFields['PROPERTY_SERVICE_ID_VALUE']] = $arFields;
            }
        }

        foreach ($serviceOrders as $serviceOrder) {

            $number1c = intval($serviceOrder['SystemOrderNumber']);

            if ($number1c < 7463845) {
                continue;
            }

            $order = OrderModel::create(\Bitrix\Main\Context::getCurrent()->getSite());

            $deliveryDateUnix = strtotime($serviceOrder['DeliveryDateTime']);

            $deliveryDate = date('d.m.Y', $deliveryDateUnix);
            $deliveryTime = date('H:i', $deliveryDateUnix);

            switch ($serviceOrder['State']) {
                case 'Отмена':
                    $order->setField('CANCELED', 'Y');
                    $newStatus = 'N';
                    break;
                case 'На кухне':
                    $newStatus = 'K';
                    break;
                case 'У водителя':
                    $newStatus = 'D';
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
                default:
                    $newStatus = 'N';
            }

            $order->setFieldNoDemand('STATUS_ID', $newStatus);

            $order->setField('ACCOUNT_NUMBER', $serviceOrder['OrderNumber']);
            $order->setField('DATE_INSERT', new \Bitrix\Main\Type\DateTime(strtotime($serviceOrder['OrderDate']) + 3600 * 8, 'U'));

            $order->setPersonTypeId(1);

            $order->setPropValue('CODE', 'DATE', $deliveryDate);
            $order->setPropValue('CODE', 'TIME', $deliveryTime);
            $order->setPropValue('CODE', 'PHONE', $phone);
            $order->setPropValue('CODE', 'BONUSES', $serviceOrder['BonusUsed']);
            $order->setPropValue('CODE', 'ADDRESS', $serviceOrder['Adress']);

            if (!$serviceOrder['SelfPickup']) {
                $order->setFieldNoDemand('DELIVERY_ID', 1);
            } else {
                $order->setFieldNoDemand('DELIVERY_ID', 2);
            }

            if (preg_match('/нлайн/i', $serviceOrder['TipOplata'])) {
                $order->setPropValue('CODE', 'PAYMENT_TYPE', 'online');
            } else if (preg_match('/Наличные/', $serviceOrder['TipOplata'])) {
                $order->setPropValue('CODE', 'PAYMENT_TYPE', 'cash');
            } else {
                $order->setPropValue('CODE', 'PAYMENT_TYPE', 'card');
            }

            $order->setField('USER_DESCRIPTION', $serviceOrder['Comment']);

            $basket = Basket::create(SITE_ID);

            $orderPrice = 0;
            $haveProduct = false;

            foreach ($serviceOrder['Sostav'] as $oitem) {

                //if (!$oitem['GoodsCode']) continue;

                $product = null;
                $constructorBasket = [];

                if ($oitem['Constructor'] && in_array($oitem['GoodsCode'], [43816])) {

                    $osnovaProduct = $productsByServiceId[$oitem['GoodsCode']];

                    if (!$osnovaProduct)
                        continue;

                    $constructorBasket[$osnovaProduct['ID']] = 1;

                    $buildLines = explode(';', $oitem['Constructor']);

                    foreach ($buildLines as $buildLine) {
                        if (!$buildLine)
                            continue;

                        list($buildLineName, $buildLineInfo) = explode(':', $buildLine);
                        list($buildLineId, $buildLineInfoRight) = explode('=', $buildLineInfo);
                        list($buildLinePrice, $buildLineQuantity) = explode('*', $buildLineInfoRight);

                        $buildLineId = intval($buildLineId);

                        if ($buildLineId) {
                            $sostavProduct = $productsByServiceId[$buildLineId];
                            if ($sostavProduct) {
                                $constructorBasket[$sostavProduct['ID']] = intval($buildLineQuantity);
                            }
                        }
                    }

                    if (count($constructorBasket) > 2) {
                        $product = $this->container->getCatalogService()->ensureConstructorElement([
                            'items' => $constructorBasket,
                            'sectionCode' => 'wok',
                        ]);
                    }

                } else {
                    $product = $productsByServiceId[$oitem['GoodsCode']];
                }

                if (!$product) {
                    continue;
                }

                $haveProduct = true;

                $basketItem = $basket->createItem('catalog', $product['ID']);
                $basketItemFields = [
                    'QUANTITY' => $oitem['Quantity'],
                    // 'PRICE' => $oitem['Price'],
                    'CUSTOM_PRICE' => 'Y',
                    'CURRENCY' => $currencyCode,
                    'LID' => SITE_ID,
                    'NAME' => $product['NAME'],
                    'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
                ];
                $basketItem->setFields($basketItemFields);
                $basketItem->setFieldNoDemand('ID', $basketItemIndex);
                $props = [];
                $basketItem->getPropertyCollection()->setProperty($props);

                $orderPrice += $oitem['Price'] * $oitem['Quantity'];
                $basketItemIndex++;
            }

            $order->setBasket($basket);
            $order->setFieldNoDemand('PRICE', $orderPrice);
            $order->setFieldNoDemand('ID', $index + 1);

            $exclude = !$haveProduct;

            if (!$exclude) {
                $orders[] = $order;
                $index++;
                if ($limit && ($index >= $limit))
                    break;
            }
        }

        $data = [
            '$serviceParams' => $serviceParams,
            '$serviceOrders' => $serviceOrders,
            '$orders' => $orders,
        ];

        // \Safe\file_put_contents(__DIR__.'/t.json', \Safe\json_encode($data));
        //die(json_encode($data));

        return $orders;
    }

    function getOrdersHistoryByCurrentUser($filter, $nav = [])
    {
        $user = $this->container->getUser();

        if (!$user || (!$phone = $user->getPhone()))
            return [];

        $orders = $this->getOrdersHistoryByPhone($phone, $filter, $nav);

        return $orders;
    }
}



