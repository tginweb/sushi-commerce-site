<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Model;
use Main\Lib\Date\DateTimeDb;

class DeliveryCalculate extends D7Model
{
    const PROPS_ALL_PUBLIC = true;

    public static function tableName()
    {
        return 'vorder_reserve';
    }

    public function getTimeMode()
    {
        return $this['UF_TIME_MODE'];
    }

    public function isTimeModeNearest()
    {
        return $this['UF_TIME_MODE'] === 'nearest';
    }

    public function isTimeModeCustom()
    {
        return $this['UF_TIME_MODE'] === 'custom';
    }

    public function isSuccess()
    {
        return $this['UF_RESULT_STATUS'] === 'success';
    }

    public function addServiceResult($result)
    {
        $this['UF_RES_STATUS'] = !$result['error'] ? 'success' : $result['error'];
        $this['UF_RES_TIME'] = DateTimeDb::fromTimestamp($result['timeAvailable']);
        $this['UF_RES_DELIVER_PRICE'] = $result['SummaDelivery'];
        $this['UF_RES_OFFICE_1C_ID'] = $result['IdPodrazdel'];

        $this['UF_RES_OFFICE_ID'] = $result['serviceId'];
    }

    public function fillFromOrder($deilveryType, OrderModel $order)
    {
        $this['UF_ORDER_ID'] = $order->getId();
        $this['UF_VORDER_ID'] = $order->getPropVal('VORDER_ID');
        $this['UF_ORDER_PRICE'] = $order->getPrice();

        $this['UF_PHONE'] = $order->getPhone();

        $this['UF_DELIVERY_HASH'] = $order->getDeliveryDataHash();
        $this['UF_DELIVERY_ADDRESS'] = $order->getAddressFull();

        if ($order->getCoordinates()) {
            $this['UF_ADDRESS_COORDS'] = $order->getCoordinates()->getStringLatLon();
        }

        $this['UF_TRANSPORT_TYPE'] = $deilveryType ?: $order->getTransportType();
        $this['UF_TIME_MODE'] = $order->getPropVal('TIME_MODE');

        $this['UF_NEED_TIME'] = DateTimeDb::fromTimestamp($order->getDeliveryDateTimeValue());
    }
}
