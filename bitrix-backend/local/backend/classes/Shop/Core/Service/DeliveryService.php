<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Sale\Delivery\Services\Manager;
use Main\Lib\Date\DateTimeDb;
use Main\Service\BaseService;
use Shop\Core\Entity\Basket;
use Shop\Core\Entity\DeliveryCalculate;
use Shop\Core\Entity\OrderModel;

class DeliveryService extends BaseService
{
    public $list = null;

    function getDeliveryList()
    {
        if (!isset($this->list)) {
            $this->list = Manager::getActiveList();
        }
        return $this->list;
    }

    function getDeliveryPrice($date)
    {
        if (!$date)
            $date = date('d.m.Y');
        $info = $this->getDeliveryPrices();
        if ($date && $info[$date]) return $info[$date];
        return $info['default'];
    }

    function getDeliveryPrices()
    {
        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $date = \COption::GetOptionString("grain.customsettings", 'DELIVERY_' . $i . '_DATE');
            $price = \COption::GetOptionString("grain.customsettings", 'DELIVERY_' . $i . '_PRICE');
            if ($date && $price) {
                $items[$date] = $price;
            }
        }
        $items['default'] = \COption::GetOptionString("grain.customsettings", 'DELIVERY_PRICE') ?: 99;
        return $items;
    }

    function deliveryCalculate($deliveryType, OrderModel $order, Basket $basket = null, $params = [])
    {
        $params += [
            'fakeBasketIfEmpty' => true,
        ];

        if ($basket) {
            $basketData = $basket->getBasketItemsCollectionInstance()->get1cData();
        } else if ($params['fakeBasketIfEmpty']) {
            $basketData = [
                [
                    'Price' => 300,
                    'Quantity' => 1,
                    'GoodsCode' => 847190,
                    'Aktsia' => false,
                ]
            ];
        }

        $needTime = $order->getDeliveryDateTimeValue();
        $coords = $order->getCoordinates();

        $serviceFields = [
            'TIME_MODE' => $order->getPropVal('TIME_MODE'),
            'TIME' => $needTime->formatToDateTime(),
            'ADDRESS' => $order->getAddressFor1c(false),
            'SESSION_ID' => session_id() ?: '8j8jj8j',
            'IS_SELF_PICKUP' => $deliveryType === 'pickup',
            'BASKET' => $basketData,
            'PHONE' => $order->getPropVal('PHONE')
        ];

        if ($coords && $coords->filled) {
            $serviceFields['GEO_LON'] = $coords->getLon();
            $serviceFields['GEO_LAT'] = $coords->getLat();
        }

        $calc = new DeliveryCalculate(null, [
            'ID' => 0,
            'UF_VORDER_ID' => $this->container->getVorder()->id,
            'UF_REQUEST_TIME' => DateTimeDb::now()
        ]);

        $calc->fillFromOrder($deliveryType, $order);

        $result = $this->container->get1СService()->apiOrderReserve($needTime, $serviceFields);

        $calc->addServiceResult($result);

        $calc->save();

        return $calc;
    }
}



