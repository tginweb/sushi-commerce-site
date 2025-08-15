<?php

namespace Shop\Core\Template;

use Main\Entity\User\UserModel;
use Main\Model\EventTemplate;
use Shop\Core\Entity\OrderModel;

class Order extends EventTemplate
{

    public $orderId;

    /**
     * @var OrderModel
     */
    public $order;

    function initFields($fields)
    {
        if (isset($fields['ENTITY_ID'])) {
            $this->orderId = $fields['ENTITY_ID'];
        } else if (isset($fields['ORDER_REAL_ID'])) {
            $this->orderId = $fields['ORDER_REAL_ID'];
        }

        $this->getOrder();
    }

    function getOrder()
    {
        if (!isset($this->order)) {
            $this->order = OrderModel::query()->getById($this->orderId);
        }
        return $this->order;
    }

    function getOrderEmail()
    {
        $orderEmail = $this->order->getPropValue('CODE', 'EMAIL');

        if (!$orderEmail) {
            $orderUserId = $this->order->getUserId();
            if ($orderUserId) {
                $orderUser = UserModel::instance($orderUserId);
                if ($orderUser) {
                    $orderEmail = $orderUser->getEmailReal();
                }
            }
        }
        return $orderEmail;
    }

    function getPaymentData()
    {
        $data = [
            'PAYMENTS' => [],
            'PAYMENT_LINK' => null,
        ];

        $paymentCollection = $this->order->getPaymentCollection();

        foreach ($paymentCollection as $payment) {

            $item = [];
            $item['ID'] = $payment->getID();
            $item['IS_INNER'] = $payment->isInner();
            $item['IS_ONLINE'] = $payment->isOnline();

            if ($payment->isInner()) {
                $item['NAME'] = 'Бонусами';
                $item['SUM'] = $payment->getSum();
                $item['PAID'] = $payment->isPaid();
            } else {
                $item['NAME'] = $payment->getPaymentSystemName();
                $item['SUM'] = $payment->getSum();
                $item['PAID'] = $payment->isPaid();
                if ($payment->isOnline()) {
                    $item['ONLINE_PAY_URL'] = $payment->getPaymentUrl(['absolute' => true]);
                    $item['ONLINE_CAN_PAY_NOW'] = true;
                }
            }
            $data['PAYMENTS'][] = $item;
        }

        return $data;
    }

    function getPropsDataGrouped($propsCodes = [])
    {
        $orderPropertiesListGroup = \CSaleOrderPropsGroup::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"],
            [],
            false,
            false,
            ["ID", "PERSON_TYPE_ID", "NAME", "SORT"]
        );

        while ($orderPropertyGroup = $orderPropertiesListGroup->GetNext()) {
            $groups[$orderPropertyGroup['ID']] = $orderPropertyGroup;
        }

        $props = $this->getPropsData($propsCodes);

        $result = [];

        foreach ($props as $prop) {
            $result[$prop['GROUP_ID']]['ITEMS'][] = $prop;
        }

        foreach ($result as $groupId => &$groupData) {
            if ($groupId)
                $groupData['NAME'] = $groups[$groupId]['NAME'];
        }

        return $result;
    }

    function getPropsData($propsCodes = [])
    {
        $items = [];

        foreach ($propsCodes as $propCode => $propInfo) {

            $item = [];

            if (is_numeric($propCode)) {
                $propInfo = [
                    'CODE' => $propInfo
                ];
            } else {

                if (!isset($propInfo['CODE']))
                    $propInfo['CODE'] = $propCode;
            }

            $prop = $this->order->getProp('CODE', $propInfo['CODE']);

            $item['NAME'] = $propInfo['NAME'] ?? $prop['NAME'];
            $item['VALUE'] = $prop['VALUE_VIEW'];
            $item['GROUP_ID'] = $prop['GROUP_ID'];

            $items[] = $item;
        }

        return $items;
    }

    function getBasketData()
    {
        $basketItems = $this->order->getBasketExt()->getBasketItemsCollectionInstance()->getClientData();

        foreach ($basketItems as $basketItem) {

            if (!$basketItem["QUANTITY"]) continue;

            $item = [];

            $m = \Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($basketItem['PRODUCT_ID']);

            $quantity = $basketItem["QUANTITY"];

            if (($quantity - round($quantity)) > 0) {
                $quantity = round($quantity, 2);
            } else {
                $quantity = round($quantity);
            }

            $itemImageUrl = $basketItem["PRODUCT"]['IMAGE'];

            $item['IMAGE'] = $itemImageUrl;
            $item['NAME'] = $basketItem["NAME"];
            $item['QUANTITY'] = $quantity;
            $item['MEASURE'] = $m[$basketItem['PRODUCT_ID']]["MEASURE"]["SYMBOL_RUS"];
            $item['FINAL_PRICE'] = $basketItem['FINAL_PRICE'];

            $items[] = $item;
        }

        return $items;
    }


    function getDeliveryData()
    {
        $arFields["DELIVERY_PRICE"] = $this->order->getDeliveryPrice();

        if ($arFields["DELIVERY_PRICE"] > 0) {
            $arFields["DELIVERY_PRICE_TEXT"] = $arFields["DELIVERY_PRICE"] . 'руб.';
        } else {
            $arFields["DELIVERY_PRICE_TEXT"] = "бесплатно";
        }

        return $arFields;
    }

    function getComputed()
    {
        return [
            'ORDER_HASH_URL' => function () {
                return $this->order->getGuestUrl();
            }
        ];
    }

}
