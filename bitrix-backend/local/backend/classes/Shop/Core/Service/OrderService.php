<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\Query;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaySystem;
use Company\Core\Entity\Office;
use COption;
use Main\Model\Result;
use Main\Service\BaseService;
use Project\C1\Core\Provider\Base as Provider1CBase;
use Shop\Core\Entity\BasketItem;
use Shop\Core\Entity\DeliveryCalculate;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Entity\VorderCurrent;

class OrderService extends BaseService
{
    public $activeOrders;

    function register($scopes = [])
    {
        $events = \Bitrix\Main\EventManager::getInstance();
        $events->addEventHandler('sale', 'OnSaleOrderPaid', [$this, 'OnSaleOrderPaid']);
        // $events->addEventHandler('sale', 'OnSaleOrderSaved', [$this, 'OnSaleOrderSaved']);
        $this->container->addFilter('mail:event.preprocessor.order', [$this, 'callEventPreprocessorOrder']);
    }

    function OnSaleOrderPaid(Main\Event $event)
    {
        $order = $event->getParameter("ENTITY");
        $orderNumber = $order->getField('ACCOUNT_NUMBER');
        $isNew = $event->getParameter("IS_NEW");
        $basket = $order->getBasket();

        $saleService = $this->container->getSaleService();
    }

    function callEventPreprocessorOrder(&$arFields, &$lid)
    {
        if (!empty($arFields['ENTITY_ID'])) {
            $order = OrderModel::query()->getById($arFields['ENTITY_ID']);
        } else if (!empty($arFields['ORDER_REAL_ID'])) {
            $order = OrderModel::query()->getById($arFields['ORDER_REAL_ID']);
        } else if (!empty($arFields['ORDER_ID'])) {
            $order = OrderModel::query()->filter(['ACCOUNT_NUMBER' => $arFields['ORDER_ID']])->first();
        }
        if ($order) {
            $arFields += [
                "ENTITY_ID" => $order->getId(),
                "ORDER_REAL_ID" => $order->getId(),
                "ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
                "ACCOUNT_NUMBER" => $order->getField("ACCOUNT_NUMBER"),
                "ORDER_DATE" => $order->getDateInsertFormatted(),
                "ORDER_PRICE" => $order->getPrice(),
                "PRICE" => $order->getPrice(),
                "ORDER_USER" => $order->getBuyerName(),
                "EMAIL" => $order->getBuyerEmail(),

                "SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@" . $_SERVER['SERVER_NAME']),
                "BCC" => COption::GetOptionString("sale", "order_email", "order@" . $_SERVER['SERVER_NAME']),
            ];
        }
        return $arFields;
    }

    function createOrder($basketItems, $orderProps = [], $fields = [])
    {
        global $USER;

        Bitrix\Main\Loader::includeModule("sale");
        Bitrix\Main\Loader::includeModule("catalog");

        // Допустим некоторые поля приходит в запросе
        $request = Context::getCurrent()->getRequest();

        $siteId = Context::getCurrent()->getSite();
        $currencyCode = CurrencyManager::getBaseCurrency();

        // Создаёт новый заказ
        $order = Order::create($siteId, $fields['USER_ID']);
        $order->setPersonTypeId(1);
        $order->setField('CURRENCY', $currencyCode);

        if ($fields['COMMENT']) {
            $order->setField('USER_DESCRIPTION', $fields['COMMENT']);
        }

        // Создаём корзину с одним товаром
        $basket = Basket::create($siteId);

        foreach ($basketItems as $basketItem) {
            $item = $basket->createItem('catalog', $basketItem['PRODUCT_ID']);
            $basketItem += [
                'QUANTITY' => 1,
                'CURRENCY' => $currencyCode,
                'LID' => $siteId,
                'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
            ];
            $item->setFields($basketItem);
        }

        $order->setBasket($basket);

        // Создаём одну отгрузку и устанавливаем способ доставки - "Без доставки" (он служебный)
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();

        if ($fields['DELIVERY_ID']) {
            $deliveryId = $fields['DELIVERY_ID'];
        } else {
            $deliveryId = Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId();
        }

        $service = Delivery\Services\Manager::getById($deliveryId);

        $shipment->setFields(array(
            'DELIVERY_ID' => $service['ID'],
            'DELIVERY_NAME' => $service['NAME'],
        ));

        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($basket->getBasketItems() as $item) {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        // Создаём оплату со способом #1
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();

        $paySystemService = PaySystem\Manager::getObjectById($fields['PAY_SYSTEM_ID']);

        $payment->setFields(array(
            'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
            'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
        ));

        $payment->setField('SUM', $order->getPrice());

        // Устанавливаем свойства
        foreach ($orderProps as $propCode => $propValue) {
            $order->setPropValue('CODE', $propCode, $propValue);
        }

        // Сохраняем
        $order->doFinalAction(true);
        $result = $order->save();
        $orderId = $order->getId();

        if ($fields['PAID']) {
            \CSaleOrder::PayOrder($orderId, "Y");
        }

        return $order;
    }

    function getUserOrdersCount($userId, $cacheItem = null)
    {
        if ($cacheItem)
            $cacheItem->expiresAfter(3600);

        $filter = ['USER_ID' => $userId];
        $result = Order::getList(['filter' => $filter])->getSelectedRowsCount();
        return $result;
    }

    function getUserOrdersCountCached($userId)
    {
        return $this->container->getCacheService()->getCachedCallback('sale:user.orders.count', [$this, 'getUserOrdersCount'], [$userId]);
    }

    function getUserTopSaleElements($userId)
    {
        $arIblock = array(28, 29);

        $query = new Query(BasketTable::getEntity());

        $query->registerRuntimeField("CNT", array('data_type' => 'integer', 'expression' => array('COUNT(%s)', 'PRODUCT_ID')));

        //$main_query->setFilter(array("PRODUCT.IBLOCK.IBLOCK_ID" => $arIblock));

        $query->setFilter(array("USER.ID" => $userId))
            ->setOrder(array("CNT" => "DESC"))
            ->setGroup(array("PRODUCT_ID"))
            ->setSelect(array("CNT", "PRODUCT_ID", "IBLOCK" => "PRODUCT.IBLOCK.IBLOCK_ID"))
            ->setLimit(5);

        $rs = $query->exec();

        $result = [];
        while ($item = $rs->Fetch()) {
            $result[$item['PRODUCT_ID']] = $item;
        }

        return $result;
    }


    function getActiveOrders()
    {
        if (!isset($this->activeOrders)) {
            $filter = $this->container->getOrderService()->getGuestOrUserFilter('active');
            if ($filter) {
                $query = OrderModel::ormQuery()
                    //->setComplexFilter($filter)
                    ->setSelect(['*'])
                    ->setLimit(3)
                    ->addOrder('ID', 'DESC');
                $this->activeOrders = $query->getList();
            } else {
                $this->activeOrders = [];
            }
        }
        return $this->activeOrders;
    }

    function getGuestOrUserFilterTest($mode = 'active')
    {
        $userId = $this->container->getUserId();

        $permFilter = [];

        if ($userId) {
            $permFilter['USER_ID'] = $userId;
        } else {
            $ids = VorderCurrent::getGuestOrdersIds('bitrix', [], 5);
            if (empty($ids))
                return null;
            $permFilter['ID'] = $ids;
        }

        $filter = [
                'LOGIC' => 'AND',
            ] + $permFilter;

        switch ($mode) {
            case 'active':
                $filter += $this->getFilterActiveOrders();
                break;
            case 'history':
                $filter[] = $this->getFilterHistoryOrders();
                break;
        }

        return $filter;
    }

    function getGuestOrUserFilter($mode = 'active')
    {
        $userId = $this->container->getUserId();
        $permFilter = [];

        if ($userId) {
            $permFilter['USER_ID'] = $userId;
        } else {
            $ids = VorderCurrent::getGuestOrdersIds('bitrix', [], 5);

            if (empty($ids))
                return null;

            $permFilter['@ID'] = $ids;
        }

        $filter = [
                'LOGIC' => 'AND',
            ] + $permFilter;

        switch ($mode) {
            case 'active':
                $filter += $this->getFilterActiveOrders();
                break;
            case 'history':
                $filter[] = $this->getFilterHistoryOrders();
                break;
        }

        return $filter;
    }

    function getFilterActiveOrders($filter = [])
    {
        $filter += [
            '>=DATE_UPDATE' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time() - 3600 * 24 * 15),
            '!CANCELED' => 'Y',
            '!STATUS_ID' => 'F',
            'PROP_DATETIME' => ['gt' => time() - 3600 * 48],
        ];
        return $filter;
    }

    function getFilterHistoryOrders($filter = [])
    {
        $filter += [
            'LOGIC' => 'OR',
            '<=DATE_UPDATE' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time() - 3600 * 24 * 15),
            '=STATUS_ID' => ['F'],
            '=CANCELED' => 'Y',
        ];
        return $filter;
    }

    function reserve(OrderModel $order, $params, Result $result)
    {
        $service1c = $this->container->get1СService();

        $timeMode = $params['timeMode'];
        $time = $params['time'];

        $test = false;

        if ($test) {

            if ($params['timeMode'] === 'nearest') {
                $timeAvailable = time() + 60 * 60;
                $result->setPayloadData('timeAvailable', $timeAvailable);
            } else {
                $timeAvailable = time() + 60 * 60;
                if (($timeAvailable - $time) > 60 * 5) {
                    $res['error'] = Provider1CBase::ERROR_RESERVE_TIME_BUSY;
                } else {
                    $timeAvailable = $time;
                }
                $result->setPayloadData('timeAvailable', $timeAvailable);
            }
        } else {

            $arFields = [
                'BASKET' => $order->getBasket()->getBasketItemsCollectionInstance()->get1cData($order),
                'BASKET_PRICE' => $order->getBasket()->getPrice(),
                'PHONE' => $order->getPropValue('CODE', 'PHONE'),
                'ADDRESS' => $order->getAddressFull(),
                'SESSION_ID' => session_id(),
                'TIME_MODE' => $timeMode
            ];

            if ($order->isSelfPickup()) {
                $arFields['IS_SELF_PICKUP'] = true;
                $arFields['DELIVERY_DEPARTMENT'] = $order->getServiceDepartmentId();
            } else {
                $arFields['IS_SELF_PICKUP'] = false;
                $arFields['DELIVERY_DEPARTMENT'] = 1;
            }

            if ($coords = $order->getCoordinates()) {
                $arFields['GEO_LON'] = $coords[0];
                $arFields['GEO_LAT'] = $coords[1];
            }


            if ($timeMode === 'nearest') {
                $timestamp = time();
            } else {
                $timestamp = $params['time'];

                if (!is_numeric($timestamp)) {
                    $timestamp = $order->getDeliveryDateTimeValue();
                }
            }

            $arFields['TIME'] = $timestamp;

            $reserveModel = DeliveryCalculate::create([]);

            $reserveModel['UF_ORDER_ID'] = $order->getId();
            $reserveModel['UF_VORDER_ID'] = $order->getPropValue('CODE', 'VORDER_ID');
            $reserveModel['UF_ORDER_PRICE'] = $order->getPrice();

            $reserveModel['UF_DELIVERY_HASH'] = $order->getDeliveryDataHash();
            $reserveModel['UF_DELIVERY_ADDRESS'] = $order->getAddressFull();
            $reserveModel['UF_TRANSPORT_TYPE'] = $order->getTransportType();
            $reserveModel['UF_TIME_MODE'] = $timeMode;

            $res = $service1c->apiOrderReserve($arFields);

            $timeAvailable = null;

            if ($params['timeMode'] === 'nearest') {
                if (!$res['error']) {
                    $timeAvailable = $res['timeAvailable'];
                }
            } else {
                $timeAvailable = $res['timeAvailable'];
            }

            if ($timeAvailable) {
                $result->setPayloadData('timeAvailable', $timeAvailable);
            }

            $reserveModel['UF_RESULT_STATUS'] = $res['error'] ?? 'success';
            $reserveModel['UF_SERVICE_OFFICE'] = $res['IdPodrazdel'];
            $reserveModel['UF_DELIVERY_PRICE'] = $res['deliveryPrice'] ?: 0;

            $reserveModel->save();
        }

        if ($res['error']) {

            $errorMessage = null;

            switch ($res['error']) {
                case  Provider1CBase::ERROR_SERVICE_UNAVAILABLE:
                    $errorMessage = 'Невозможно проверить время доставки, попробуйте еще раз';

                    if (!$order->isSelfPickup()) {
                        $res['deliveryPrice'] = 800;
                    }

                    break;
                case  Provider1CBase::ERROR_RESERVE_TIME_BUSY:
                    $errorMessage = 'Время занято';
                    break;
                case  Provider1CBase::ERROR_RESERVE_ADDRESS_NOT_FOUND:
                    $errorMessage = 'Адрес не найден';
                    break;
            }

            //$response->addError($errorMessage, ['status' => $res['error']]);

            $result->setStatus($res['error']);
        } else {
            if ($params['timeSave']) {
                $order->setPropValue('CODE', 'DATE', date('d.m.Y', $timeAvailable));
                $order->setPropValue('CODE', 'TIME', date('H:i', $timeAvailable));
            }
        }

        if ($reserveModel && (!$res['error'] || $res['error'] === Provider1CBase::ERROR_RESERVE_TIME_BUSY)) {
            $reserveModel->fillOrderReserveFields($order);
        }

        if ($order->isSelfPickup() || $params['forceDeliveryFree']) {
            $res['deliveryPrice'] = 0;
            $res['deliveryFreeFromPrice'] = 0;
        }

        $prevDeliveryPrice = $order->getCustomDeliveryPrice();

        if ($res['deliveryPrice']) {

            $order->setPropValue('CODE', 'DELIVERY_PRICE', $res['deliveryPrice']);
            $order->setPropValue('CODE', 'DELIVERY_FREE_FROM_PRICE', $res['deliveryFreeFromPrice']);
            $order->setPropValue('CODE', 'DELIVERY_FREE_UPDATED_TIME', time());

            $order->createOrderShipment($order->getDeliveryId());
            $order->getTotal(true);
        }

        $result->setPayloadData('deliveryPrice', $res['deliveryPrice']);
        $result->setPayloadData('deliveryFreeFromPrice', $res['deliveryFreeFromPrice']);
        $result->setPayloadData('deliveryHash', $reserveModel['UF_DELIVERY_HASH']);

        if ($departmentServiceId = $res['IdPodrazdel']) {

            $result->setPayloadData('departmentServiceId', $departmentServiceId);
            $result->setPayloadData('departmentServiceName', $this->container->get1СService()->getServiceDeparmentName($departmentServiceId));

            if ($office = Office::getByServiceId($departmentServiceId)) {
                $order->setDeliveryDepartmentId($office['ID']);
                $result->setPayloadData('departmentId', $office['ID']);
                $result->setPayloadData('departmentName', $office['NAME']);

                if ($order->isSelfPickup()) {
                    $order->setPropValue('CODE', 'DELIVERY_DEPARTMENT_ID', $office['ID']);
                } else {
                    $order->setPropValue('CODE', 'PICKUP_DEPARTMENT_ID', $office['ID']);
                }
            }
        }

        return $res;
    }

    function roundOrderPrice($price)
    {
        return floor($price);
    }
}



