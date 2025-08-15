<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PriceMaths;
use Bitrix\Sale\Registry;
use Bitrix\Sale\Result;
use Bitrix\Sale\Shipment;
use Bitrix\Sale\ShipmentCollection;
use Bitrix\Sale\ShipmentItem;
use Bitrix\Sale\ShipmentItemCollection;
use Company\Core\Entity\Office;
use Geo\Core\Lib\GeoCoordinates;
use Main\DI\Containerable;
use Main\Entity\D7\D7Adapter;
use Main\Entity\IBlock\ElementCollection;
use Main\Entity\User\UserModel;
use Main\Lib\Date\DateTime;
use Main\Traits\Contextable;
use TG\Main\Helper;
use TG\Shop\Core\Entity\Wrapper\Paytype;
use function TG\Shop\Core\Entity\MakeTimeStamp;
use const TG\Shop\Core\Entity\LANGUAGE_ID;

class OrderModel extends Sale\Order
{
    use Contextable;
    use Containerable;

    static $adapter;
    static $statuses = null;
    static $statusesByType = [];

    public $extDelivery;
    public $extPaysystem;
    public $userModel;

    public $propsById = null;
    public $propsByCode = [];
    public $propsByRole = [];
    public $propsProfiled = [];

    public $calculated = false;
    public $totals = null;
    public $propsValuesFast;
    public $accessCode;


    /* @var OrderAttributeCollection */
    public $attributes;

    public $overridePropsValues = [];

    public $computedData = [];

    public function computed($key, $callback = null, $refetch = false)
    {
        if (!$callback)
            return $this->computedData[$key];
        if (!isset($this->computedData[$key]) || $refetch) {
            $this->computedData[$key] = $callback();
        }
        return $this->computedData[$key];
    }

    public static function ormQuery()
    {
        return new OrderOrmQuery(OrderTable::getEntity());
    }

    static function createFromArray(array $fields = array())
    {
        $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
        $orderClassName = $registry->getOrderClassName();

        return new $orderClassName($fields);
    }

    /**
     * @return OrderQuery
     */
    public static function query()
    {
        return new OrderQuery(static::instantiateAdapter(), OrderModel::class, OrderCollection::class);
    }

    /**
     * @return D7Adapter
     */
    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Order::class);
    }

    public function getStatusName()
    {
        if ($this->isCanceled()) return 'Отменен';
        $info = $this->getStatusInfo();
        return $info && !empty($info['NAME']) ? $info['NAME'] : null;
    }

    public function getStatusInfo()
    {
        return static::statusInfo($this->getField('STATUS_ID'));
    }

    static function statusInfo($id)
    {
        $statuses = static::statusesInfo();
        return $statuses[$id] ?? null;
    }

    static function statusesInfo($type = null)
    {
        if (!isset(static::$statuses)) {
            static::$statuses = [];

            $dbRes = \Bitrix\Sale\Internals\StatusTable::getList(array(
                'select' => array('ID', 'COLOR', 'SORT', 'TYPE', 'NAME' => 'Bitrix\Sale\Internals\StatusLangTable:STATUS.NAME'),
                'filter' => array(
                    '=Bitrix\Sale\Internals\StatusLangTable:STATUS.LID' => LANGUAGE_ID,
                    // '=TYPE' => 'D'
                ),
                'order' => array('SORT' => 'ASC')
            ));

            while ($status = $dbRes->fetch()) {
                static::$statuses[$status["ID"]] = $status;
                static::$statusesByType[$status['TYPE']][$status["ID"]] = $status;
            }
        }

        return $type ? static::$statusesByType[$type] : static::$statuses;
    }

    public function getStatusColor()
    {
        if ($this->isCanceled()) return 'rgba(244, 78, 33, 1)';
        $info = $this->getStatusInfo();
        return $info && !empty($info['COLOR']) ? $info['COLOR'] : null;
    }

    public function getComputedStatusName()
    {
        if ($this->isCanceled()) return 'Отменен';
        $statusId = $this->getComputedStatusId();
        $info = static::statusInfo($statusId);

        if ($info && $this->isSelfPickup() && $info['NAME'] === 'Доставка') {
            $info['NAME'] = 'Самовывоз';
        }

        return $info && !empty($info['NAME']) ? $info['NAME'] : null;
    }

    public function getComputedStatusId()
    {
        $statusId = $this->getField('STATUS_ID');
        if ($statusId === 'P') {
            if ($this->getPropVal('IS_ACCEPTED') !== 'Y' && ($this->getId() > 2262625 || $this->getId() === 2262621)) {
                return 'N';
            }
        }
        return $statusId;
    }

    public function getComputedStatusColor()
    {
        if ($this->isCanceled()) return 'rgba(244, 78, 33, 1)';
        $statusId = $this->getComputedStatusId();
        $info = static::statusInfo($statusId);
        return $info && !empty($info['COLOR']) ? $info['COLOR'] : null;
    }

    function getProps($refetch = false)
    {
        if (isset($this->propsById) && !$refetch) return $this->propsById;

        //$this->container->getGraphqlService()->timerCollectorStart('getProps');

        $this->propsById = [];
        $this->propsByCode = [];
        $this->propsByRole = [];
        $this->propsProfiled = [];

        $personTypeId = $this->getPersonTypeId();

        /** @var OrderPropertyValue $propValueModel */
        foreach ($this->getPropertyCollection() as $propValueModel) {

            $prop = $propValueModel->getPropModel();

            if (!$prop)
                continue;

            $propId = $propValueModel->getField('ORDER_PROPS_ID');
            $propCode = $prop->getCode();

            $propValue = isset($this->overridePropsValues[$propCode]) ? $this->overridePropsValues[$propCode] : $this->overridePropsValues[$propId];

            if (isset($propValue)) {
                $propValueModel->setField('VALUE', $propValue);
            }

            if ($personTypeId && ($prop->getPersonTypeId() != $personTypeId)) continue;

            if ($prop->getField('ACTIVE') !== 'Y') continue;

            $this->propsById[$prop['ID']] = $propValueModel;
            $this->propsByCode[$prop['CODE']] = &$this->propsById[$prop['ID']];
            $this->propsByRole[$prop['ROLE']] = &$this->propsById[$prop['ID']];

            if ($prop->isProfile()) {
                $this->propsProfiled[$prop['ID']] = &$this->propsById[$prop['ID']];
            }
        }

        // $this->container->getGraphqlService()->timerCollectorEnd('getProps');

        return $this->propsById;
    }

    function getDeliveryId()
    {
        if ($service = $this->getDelivery()) {
            return $service['ID'];
        }
    }

    function getDelivery()
    {
        if (!isset($this->extDelivery) || ($this->extDelivery['ID'] != $this->loadDeliveryId())) {
            $this->extDelivery = $this->loadDelivery();
        }
        return $this->extDelivery;
    }

    function loadDelivery()
    {
        if ($id = $this->loadDeliveryId()) {
            return \Bitrix\Sale\Delivery\Services\Manager::getById($id);
        }
        return false;
    }

    function getDeliveryName()
    {
        if ($service = $this->getDelivery()) {
            return $service['NAME'];
        }
    }

    function getPaysystemId()
    {
        $paysystem = $this->getPaysystem();
        if ($paysystem) {
            return $paysystem->getField('ID');
        }
    }

    function getPaysystem()
    {
        if (!isset($this->extPaysystem)) {
            $this->extPaysystem = $this->loadPaySystem();
        }
        return $this->extPaysystem;
    }

    function loadPaySystem()
    {
        $paysystemId = null;
        $paysystemIdByUnpaidPayments = null;
        $paysystemIdByPaidPayments = null;

        foreach ($this->getPaymentCollection() as $payment) {
            if ($payment->isInner()) continue;

            if (!$payment->isPaid()) {
                $paysystemIdByUnpaidPayments = $payment->getPaymentSystemId();
            } else {
                $paysystemIdByPaidPayments = $payment->getPaymentSystemId();
            }
        }

        if ($paysystemIdByUnpaidPayments) {
            $paysystemId = $paysystemIdByUnpaidPayments;
        } else if ($this->getField('PAY_SYSTEM_ID')) {
            $paysystemId = $this->getField('PAY_SYSTEM_ID');
        } else {
            $paysystemId = $paysystemIdByPaidPayments;
        }

        if ($paysystemId) {
            return PaySystem\Manager::getObjectById($paysystemId);
        }
    }

    /**
     * Return payment collection
     *
     * @return Sale\PaymentCollection
     */
    public function getPaymentCollection()
    {
        if (empty($this->paymentCollection)) {
            $this->paymentCollection = $this->loadPaymentCollection();
        }
        return $this->paymentCollection;
    }

    function getPaysystemName()
    {
        $paysystem = $this->getPaysystem();
        if ($paysystem) {
            return $paysystem->getField('NAME');
        }
    }

    function getPropsValuesByCode()
    {
        $this->getProps();
        $result = [];
        foreach ($this->propsByCode as $prop) {
            $result[$prop->getField('CODE')] = $prop->getField('VALUE');
        }
        return $result;
    }

    function setField($name, $value)
    {
        $this->onChangeField($name, $value);
        return parent::setField($name, $value);
    }

    function onChangeField($name, $value)
    {
        switch ($name) {
            case 'DELIVERY_ID':
                $this->onChangeDelivery();
                break;
            case 'PAY_SYSTEM_ID':
                $this->onChangePaysystem();
                break;
        }
    }

    function onChangeDelivery()
    {
        $this->extDelivery = null;
    }

    function onChangePaysystem()
    {
        $this->extPaysystem = null;
    }

    function isCanPay()
    {
        return
            $this->haveDetails() &&
            $this->isActive() &&
            !$this->isPaid() &&
            !$this->isCanceled();
    }

    function haveDetails()
    {
        return $this->getPropVal('DETAILS', 'Y') === 'Y';
    }

    function getPropValue($by, $propName, $def = null)
    {
        $prop = $this->getProp($by, $propName);
        if ($prop) {
            return $prop->getValueDecoded() ?? $def;
        }
        return $def;
    }

    /**
     * @return OrderPropertyValue
     */
    function getProp($by, $val)
    {
        $this->getProps();

        switch ($by) {
            case 'ID':
                return $this->propsById[$val];
            case 'CODE':
                return $this->propsByCode[$val];
            case 'ROLE':
                return $this->propsByRole[$val];
        }
    }

    function isActive()
    {
        return !$this->isFinished() && !$this->isCanceled() && ($this->getAgeDays() < 20);
    }

    function isFinished()
    {
        return $this->getField('STATUS_ID') === 'F';
    }

    function getAgeDaysActual()
    {
        $insetDatetime = \DateTime::createFromFormat('U', $this->getDateInsert()->format('U'));
        $currentDatetime = new \DateTime('now');
        $interval = $insetDatetime->diff($currentDatetime);
        return $interval->format('%a');
    }

    function getAgeDays()
    {
        return 0;
        $insetDatetime = \DateTime::createFromFormat('U', $this->getDateInsert()->format('U'));
        $currentDatetime = new \DateTime();
        $interval = $insetDatetime->diff($currentDatetime);
        return $interval->format('%R%a');
    }

    function getDepartmentPaysystemId($defaultIfNotAvailable = true)
    {
        $office = $this->getDepartment();
        if ($office) {
            $id = $office->getProp('PAYSYSTEM_ID');
        }
        if (!$id && $defaultIfNotAvailable) {
            if (!$this->isSelfPickup()) {
                $id = 14;
            }
        }
        return $id;
    }

    function getDepartment()
    {
        $departmentId = $this->getDepartmentId();
        return $departmentId ? Office::query()->withViewList()->getById($departmentId) : null;
    }

    function getDepartmentId()
    {
        if (!$this->isSelfPickup()) {
            return $this->getDeliveryDepartmentId();
        } else {
            return $this->getPickupDepartmentId();
        }
    }

    public function isSelfPickup()
    {
        return $this->getTransportType() === 'pickup';
    }

    public function getTransportType()
    {
        $wrapper = $this->getDeliveryWrapper();
        return $wrapper ? $wrapper->getTransportType() : null;
    }

    function getDeliveryWrapper()
    {
        return \Shop\Core\Entity\Wrapper\Delivery\Base::wrap($this->getDelivery());
    }

    function loadDeliveryId()
    {
        if ($this->getId()) {
            $ids = $this->getDeliveryIdListExt();
            if (!empty($ids)) {
                $id = array_shift($ids);
            } else {
                $id = $this->getField('DELIVERY_ID');
            }
        } else {
            $id = $this->getField('DELIVERY_ID');
        }
        return $id;
    }

    /**
     * @return array
     */
    public function getDeliveryIdListExt(): array
    {
        $result = [];

        /** @var Shipment $shipment */
        foreach ($this->getShipmentCollection() as $shipment) {
            if ($shipment->isSystem())
                continue;

            if ($shipment->getDeliveryId() > 0) {
                $result[] = $shipment->getDeliveryId();
            }
        }

        return $result;
    }

    function getDeliveryDepartmentId()
    {
        return $this->getPropVal('DELIVERY_DEPARTMENT');
    }

    function getPickupDepartmentId()
    {
        return $this->getPropVal('PICKUP_DEPARTMENT');
    }

    function getPrice()
    {
        return parent::getPrice();
    }

    function getPayTypeWrapper()
    {
        return \Shop\Core\Entity\Wrapper\Paytype\Base::wrap($this->getPropVal('PAYMENT_TYPE', 'cash'));
    }

    function getClientData()
    {
        $result = [
            'ID' => $this->getField('ID'),
            'PAY_SYSTEM_NAME' => $this->getPaysystemName(),
            'DELIVERY_NAME' => $this->getDeliveryName(),
            'PROPS' => $this->getProps(),
            'BASKET' => $this->getBasket()->getClientData()
        ];

        return $result;
    }

    function overridePropsValues($values)
    {
        $this->overridePropsValues = $values;
    }

    function getPropsValuesMap($refetch = false)
    {
        $result = [];
        foreach ($this->getProps($refetch) as $prop) {
            $propCode = !empty($prop['CODE']) ? $prop['CODE'] : $prop['ID'];
            $result[$propCode] = $prop['VALUE'];
        }
        return $result;
    }

    function setPropValue($by, $val, $propValue)
    {
        $prop = $this->getProp($by, $val);

        if ($prop) {

            $propCode = !empty($prop['CODE']) ? $prop['CODE'] : $prop['ID'];

            $this->overridePropsValues[$propCode] = $propValue;

            $prop->setValueDecoded($propValue);
        }

        return $this;
    }

    /**
     * @return \Shop\Core\Entity\Payment
     */
    function getPaymentUnpaid()
    {
        if (!$this->isPaid()) {
            foreach ($this->getPaymentCollection() as $payment) {
                if ($payment->getField('PAID') !== 'Y') {
                    return $payment;
                }
            }
        }
    }

    function isCanPayOnline()
    {
        if ($this->container->getApp()->isMobileApp()) {
            return $this->getPaysystemIsOnline();
            // return false;
        }
        return $this->isCanPay();
    }

    function getPaysytemWrapper()
    {
        return \Shop\Core\Entity\Wrapper\Paysystem\Base::wrap($this->getPaysystem());
    }

    function getPaySystemIsOnline()
    {
        return $this->getPropVal('PAYMENT_TYPE') === 'online';
    }

    function isFinalPrice()
    {
        $statusId = $this->getField('STATUS_ID');

        $finalPriceStatus = $this->container->getConfigService()->get('SALE.ORDER_FINAL_PRICE_STATUS', []);

        return empty($finalPriceStatus) || in_array($statusId, $finalPriceStatus);
    }

    function isDelivering()
    {
        return $this->getField('STATUS_ID') === 'O';
    }

    function getPaymentUrl($paymentId = null, $options = [])
    {
        $params = [];

        $params['action'] = 'pay-initiate';
        $params['paymentId'] = $paymentId;

        return $this->getGuestUrl($params, $options);
    }

    function getGuestUrl($query = [], $options = [])
    {
        return \Main\Helper\Str::makeUrl('/order-view/' . $this->getId() . '/' . $this->getAccessHash(), $query, $options);
    }

    function getAccessHash($refetch = false)
    {
        if (!isset($this->accessCode) || $refetch) {
            $dateInsert = $this->getDateInsert()->setTimeZone(new \DateTimeZone("Europe/Moscow"));
            $timestamp = $dateInsert->getTimestamp();
            $this->accessCode = md5(
                $this->getId() .
                $timestamp .
                $this->getUserId() .
                $this->getField('ACCOUNT_NUMBER') .
                $this->getField('XML_ID')
            );
        }
        return $this->accessCode;
    }

    function getUserOrGuestUrl($query = [])
    {
        if (!$this->container->getUserId()) {
            return $this->getGuestUrl($query);
        } else {
            return $this->getUrl($query);
        }
    }

    function getUrl($params = [])
    {
        return \Main\Helper\Str::makeUrl($this->container->getEntityService()->getEntityPublicUrl('order', $this), $params);
    }

    function getDiscountPriceComp()
    {
        return $this->getTotalField('PRICE_DISCOUNT');
    }

    function getTotalField($field, $refetch = false)
    {
        $data = $this->getTotal($refetch);
        return $data[$field];
    }

    function getPriceTotal()
    {
        return $this->getTotalField('PRICE_TOTAL');
    }

    function getTotal($refetch = false, $calc = false)
    {
        if (empty($this->totals) || $refetch) {

            if ($calc) $this->calculate();

            $arResult = [];

            $arResult['ORDER_WEIGHT'] = $this->getBasket()->getWeight();

            $arResult['PRICE_BASKET'] = $this->getBasket()->getPrice();

            $arResult['PRICE_BASKET_BASE'] = $this->getBasket()->getBasePriceWithoutGifts();

            $arResult['PRICE_DELIVERY'] = PriceMaths::roundPrecision($this->getShipmentsPrice());

            $arResult['PRICE_DELIVERY_BASE'] = $this->getShipmentsBasePrice();

            //$deliveryDiscount = $this->getCustomDeliveryPrice() - $this->getDeliveryPrice();

            if ($this->getId()) {
                $discountPrice = -$this->getDiscountPrice();
                $totalPriceFull = $this->getPrice();
                $totalPriceFullBase = $arResult['PRICE_BASKET_BASE'] + $arResult['PRICE_DELIVERY_BASE'];
            } else {
                $discountPrice = -$this->getDiscountPrice();
                $totalPriceFull = $this->getPrice();
                $totalPriceFullBase = $arResult['PRICE_BASKET_BASE'] + $arResult['PRICE_DELIVERY_BASE'];
            }

            $arResult['PRICE_TOTAL'] = PriceMaths::roundPrecision($totalPriceFull);

            $arResult['PRICE_TOTAL_BASE'] = PriceMaths::roundPrecision($totalPriceFullBase);

            $bonuses = 0;

            if ($this->canUseBenefitType('bonus')) {
                $bonuses = $this->getBonuses();
            }

            $arResult['PRICE_PAY'] = $totalPriceFull - $bonuses;

            $arResult['PRICE_PAY_BASE'] = $totalPriceFullBase - $bonuses;

            $arResult['PRICE_DISCOUNT'] = PriceMaths::roundPrecision($discountPrice);

            //file_put_contents(__DIR__ . '/log.txt', 'getTotal: ' . json_encode($arResult, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            $this->totals = $arResult;
        }

        return $this->totals;
    }

    function getShipmentsPrice()
    {
        //return !$this->getPickupDepartmentId() ? $this->getCustomDeliveryPrice() : 0;
        $price = 0;
        foreach ($this->getShipmentCollection() as $shipment) {
            /** @var Shipment $shipment */
            if (!$shipment->isSystem()) {
                $price += $shipment->getPrice();
            }
        }
        return $price;
    }

    function getShipmentsBasePrice()
    {
        //return !$this->getPickupDepartmentId() ? $this->getCustomDeliveryPrice() : 0;

        $price = 0;
        foreach ($this->getShipmentCollection() as $shipment) {
            /** @var Shipment $shipment */
            if (!$shipment->isSystem()) {
                $price += $shipment->getField('BASE_PRICE_DELIVERY');
            }
        }
        return $price;
    }

    function calculate($recalc = false)
    {
        if (!$this->calculated || $recalc) {

            // рассчитываем скидки отдельно. эти данные нам пригодятся
            //$discounts = $this->getDiscount();
            //$discountsRes = $discounts->calculate();

            // производим финальную обработку
            $this->doFinalAction(true);

            $this->calculated = true;

            $this->totals = null;
        }
    }

    function getCoupon()
    {
        return $this->getCoupons()->first();
    }

    function isCouponsUsed()
    {
        return $this->getCoupons()->count();
    }

    function getCouponItemCode()
    {
        $item = $this->getCouponItem();
        return $item ? $item['code'] : null;
    }

    function getCouponItem()
    {
        $items = $this->getCouponsItems();
        return array_shift($items);
    }

    function getCouponsItems()
    {
        $items = [];
        $promocode = trim($this->getPropVal('PROMOCODE'));
        if ($promocode) {
            list($couponElementId, $couponCode) = preg_split('/\s*\:\s*/', $promocode);
            if ($couponElementId || $couponCode) {
                if (!$couponCode) {
                    if (!is_numeric($couponElementId)) {
                        $couponCode = $couponElementId;
                    } else {
                        $couponElementId = $couponElementId;
                    }
                }
                $items[] = [
                    'code' => $couponCode,
                    'elementId' => $couponElementId,
                ];
            }
        }
        return $items;
    }

    function getCoupons()
    {
        $items = $this->getCouponsItems();
        $elementIds = [];
        foreach ($items as $item) {
            if ($item['elementId']) {
                $elementIds[] = $item['elementId'];
            }
        }
        if (!empty($elementIds)) {
            return Coupon::query()->filter(['ID' => $elementIds])->withViewList()->getList();
        } else {
            return new ElementCollection([]);
        }
    }


    /**
     * @param int|null $oldUserId
     */
    public function initCouponsData($oldUserId = 0)
    {
        //$this->container->getGraphqlService()->timerCollectorStart('createOrder');
        $newUserId = $this->getUserId();
        $orderId = $this->getId();

        $params = array('userId' => $newUserId);

        if ($oldUserId) {
            if ($oldUserId != $newUserId)
                $params["oldUserId"] = $oldUserId;
        }

        if ($orderId > 0) {
            $params['orderId'] = $orderId;

            DiscountCouponsManager::init(
                DiscountCouponsManager::MODE_ORDER,
                $params
            );
        } else {
            DiscountCouponsManager::init(
                DiscountCouponsManager::MODE_CLIENT,
                $params
            );
        }
        //$this->container->getGraphqlService()->timerCollectorEnd('createOrder');
    }

    function getDateInsertFormatted($format = null)
    {
        $ts = MakeTimeStamp($this->getDateInsert()->toString());
        $currentYear = date('Y', $ts) === date('Y', time());
        $format = $format ?? $currentYear ? 'd F, H:i' : 'd F Y, H:i';
        return mb_strtolower(FormatDate($format, $ts));
    }

    function getBuyerName()
    {
        $value = $this->getPropVal('FIO');
        if (!$value && $this->getUserId()) {
            if ($orderUser = $this->getUserModel()) {
                $value = $orderUser->getNameFirstLast();
            }
        }
        return $value;
    }

    function getUserModel()
    {
        if (!$this->getUserId())
            return false;
        if (!isset($this->userModel))
            $this->userModel = UserModel::getById($this->getUserId());
        return $this->userModel;
    }

    function getBuyerEmail()
    {
        $value = $this->getPropVal('EMAIL');
        if (!$value) {
            $user = $this->getUserModel();
            $value = $user->getEmailReal();
        }
        return $value;
    }

    /**
     * Return order basket.
     *
     * @return Basket
     */
    public function getBasketExt()
    {
        return parent::getBasket();
    }

    function setVorderField($name, $value)
    {
        if (in_array($name, [
            'PERSON_TYPE_ID',
            'DELIVERY_ID',
            'PAY_SYSTEM_ID',
            'USER_DESCRIPTION'
        ])) {
            $this->setFieldNoDemand($name, $value);
        }
    }

    function setFieldNoDemand($name, $value)
    {
        $this->onChangeField($name, $value);
        parent::setFieldNoDemand($name, $value);
    }

    function getVorderFields()
    {
        $result = [];

        $fields = [
            'PERSON_TYPE_ID',
            'DELIVERY_ID',
            'PAY_SYSTEM_ID',
            'USER_DESCRIPTION'
        ];

        foreach ($fields as $field) {
            $val = $this->getField($field);
            if ($val)
                $result[$field] = $val;
        }

        return $result;
    }

    function getOnlinePayLink()
    {
        $payment = $this->getOnlinePaymentUnpaid();
        if ($payment) {
            return $this->getOnlinePaymentLink($payment->getId());
        }
    }

    function getOnlinePaymentUnpaid()
    {
        foreach ($this->getPaymentsUnpaid() as $payment) {
            if ($payment->isOnline()) return $payment;
        }
    }

    /**
     * @return \Shop\Core\Entity\Payment[]
     */
    function getPaymentsUnpaid()
    {
        $result = [];

        if (!$this->isPaid()) {
            foreach ($this->getPaymentCollection() as $payment) {
                if ($payment->getField('PAID') !== 'Y') {
                    $result[] = $payment;
                }
            }
        }

        return $result;
    }

    function getOnlinePaymentLink($paymentId)
    {
        return \Main\Helper\Str::makeUrl('/served/pay.php', [
            'ORDER_ID' => $this->getId(),
            'PAYMENT_ID' => $paymentId
        ]);
    }

    public function getCompileVars()
    {
        return [
            'ID' => $this->getId()
        ];
    }

    function getPricePay()
    {
        return $this->getTotalField('PRICE_PAY');
    }

    function getDeliveryPriceFieldsHash()
    {
        $fields = $this->getDeliveryPriceFields();
        return json_encode($fields);
    }

    function getDeliveryPriceFields()
    {
        $delivery = $this->getDelivery();

        $fields = [
            'DELIVERY_ID' => $delivery ? $delivery['ID'] : null,
            'ZONE' => $this->getPropVal('ZONE'),
            'ORDER_PRICE' => $this->getPrice()
        ];

        return $fields;
    }

    function resetTotals()
    {
        $this->totals = null;
    }

    /**
     * @param int $paySystemId
     * @return \Shop\Core\Entity\Payment
     */
    function createOrderPayment($paySystemId = null)
    {
        $order = $this;

        $paySystemId = $paySystemId ?? $this->getField('PAY_SYSTEM_ID');

        $price = $order->getPrice();

        $paymentCollection = $order->getPaymentCollection();
        $paymentCollection->clearCollection();

        $payment = $paymentCollection->createItem(
            PaySystem\Manager::getObjectById($paySystemId)
        );

        $priceBonuses = 0;

        if ($this->canUseBenefitType('bonus')) {
            $priceBonuses = $order->getBonuses();
        }

        if ($priceBonuses) {
            $priceMain = $price - $priceBonuses;
        } else {
            $priceMain = $price;
        }

        $payment->setField('SUM', ceil($priceMain));

        $payment->setField('CURRENCY', $order->getCurrency());

        if ($priceBonuses) {
            $payment = $paymentCollection->createItem(
                PaySystem\Manager::getObjectById(7)
            );
            $payment->setField('SUM', $priceBonuses);
            $payment->setField('CURRENCY', $order->getCurrency());
        }

        $this->setFieldNoDemand('PAY_SYSTEM_ID', $paySystemId);

        return $payment;
    }


    /**
     * @return Shipment
     */
    function createOrderShipment($deliveryId = 0)
    {
        $order = $this;

        $deliveryId = $deliveryId ?? $this->getField('DELIVERY_ID');

        /* @var $shipmentCollection ShipmentCollection */
        $shipmentCollection = $order->getShipmentCollection();


        foreach ($shipmentCollection as $shipment) {
            if (!$shipment->isSystem()) {
                $shipment->delete();
            }
        }

        if ($deliveryId > 0) {
            $shipment = $shipmentCollection->createItem(
                \Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)
            );
        } else {
            $shipment = $shipmentCollection->createItem();
        }


        /** @var $shipmentItemCollection ShipmentItemCollection */
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $order->getCurrency());

        foreach ($order->getBasket()->getOrderableItems() as $basketItem) {
            /**
             * @var $basketItem \Shop\Core\Entity\BasketItem
             * @var $shipmentItem ShipmentItem
             */
            $shipmentItem = $shipmentItemCollection->createItem($basketItem);
            $shipmentItem->setQuantity($basketItem->getQuantity());
        }

        $shipmentCollection->calculateDelivery();

        $this->resetTotals();

        return $shipment;
    }

    function getCustomDeliveryPrice()
    {
        if (!$this->isSelfPickup()) {
            $deliveryFreeFromPrice = $this->getPropVal('DELIVERY_FREE_FROM_PRICE') ?: 800;
            $basketPrice = $this->getBasket()->getPrice();
            if ($deliveryFreeFromPrice > $basketPrice) {
                return $deliveryFreeFromPrice - $basketPrice;
            }
        }
        return 0;
    }

    function getDeliveryPriceEnsure()
    {
        $fields = $this->getDeliveryPriceFields();
        foreach ($fields as $field => $value) {
            if (!$value) return false;
        }
        return true;
    }

    function isDeliveryCalculated()
    {
        return $this->getCustomDeliveryPrice() !== false;
    }

    function getLocation()
    {
        $code = $this->getPropVal('LOCATION');
        if (!empty($code)) {
            $location = Location::query()->getByCode($code);
            if ($location) {
                return $location;
            }
        }
    }

    public function setLocationCode($locationCode)
    {
        if ($this->getLocationCode() != $locationCode) {

            $newLocation = Location::query()->getByCode($locationCode);

            foreach (['ADDRESS', 'COORDINATES', 'ZIP'] as $role) {
                $this->setPropVal($role, '');
            }

            if ($newLocation) {
                $this->setPropVal('ZIP', $newLocation->getZip());
                $this->setPropVal('CITY', $newLocation['NAME_LANG']);
            }
        }

        return $this;
    }

    function getLocationCode()
    {
        if ($prop = $this->getProp('ROLE', 'LOCATION')) {
            return $prop['VALUE'];
        }
    }

    function resetProfileProps()
    {
        $vorderProps = $this->getPropsProfiled();

        foreach ($vorderProps as $vorderProp) {
            $this->setPropValue('ID', $vorderProp->getPropId(), $vorderProp['DEFAULT_VALUE']);
        }
    }

    function getPropsProfiled($refetch = false)
    {
        $this->getProps($refetch);
        return $this->propsProfiled;
    }

    function loadProfileProps($profileId)
    {
        //die('loadProfileProps '.$profileId);

        if (!$profileId || $profileId === 'custom')
            return false;

        /* @var $profile OrderProfile */
        $profile = is_object($profileId) ? $profileId : OrderProfile::query()->getById($profileId);

        if (!$profile) {
            return false;
        }

        $profile->prepare();
        $vorderProps = $this->getPropsProfiled();
        $profileProps = $profile->getProps();

        foreach ($vorderProps as $vorderProp) {

            $propId = $vorderProp->getPropId();
            $profileProp = $profileProps[$propId];

            if ($profileProp) {
                $this->setPropValue('ID', $propId, $profileProp['VALUE']);
            } else {
                $this->setPropValue('ID', $propId, $vorderProp['DEFAULT_VALUE']);
            }
        }

        return true;
    }

    /**
     * Set person type id of order
     *
     * @param $personTypeId
     *
     * @return Result
     */
    public function setPersonTypeIdExt($personTypeId)
    {
        if ($this->getPersonTypeId() != $personTypeId) {
            $this->setPersonTypeId($personTypeId);
            $this->propsById = null;
            $this->propertyCollection = null;
        }
    }

    public function calcDiscountProps($force = false)
    {
        if (!$this->getId() || $force) {

            $prevDiscount = $this->getField('DISCOUNT_PRICE') ?: 0;

            if (
                $this->canUseBenefitType('discount') &&
                ($discount = $this->container->getSaleClientCardService()->getOrderDiscount($this))
            ) {
                VorderCurrent::logHandler('applyDiscount', [
                    'res' => true
                ]);
                $cardDiscount = ($this->getPrice() / 100) * $discount['PERCENT'];
                $totalDiscount = $prevDiscount + $cardDiscount;
                $this->setField('DISCOUNT_PRICE', $totalDiscount);
                $this->setPropVal('DISCOUNT_PERCENT', $discount['PERCENT']);
                $this->setPropVal('DISCOUNT_REASON', $discount['NAME']);
            } else {
                VorderCurrent::logHandler('applyDiscount', [
                    'res' => false
                ]);
                $this->setPropVal('DISCOUNT_PERCENT', 0);
                $this->setPropVal('DISCOUNT_REASON', '');
            }
        }
    }

    public function applyDiscount(array $data)
    {

        if (!$this->getId()) {

            $offersSpecialSaved = $_SESSION['BASKET_SPECIAL_OFFERS'] ?? [];
            $offersSpecialResolved = $GLOBALS['SALE_DISCOUNT_DATA_SPECIAL_OFFERS'] ?? [];

            $offersRecommendationSaved = $_SESSION['BASKET_RECOMMENDATION_OFFERS'] ?? [];
            $offersRecommendationResolved = $GLOBALS['SALE_DISCOUNT_DATA_RECOMMENDATION_OFFERS'] ?? [];

            if ($offersSpecialSaved != $offersSpecialResolved) {
                $_SESSION['BASKET_SPECIAL_OFFERS'] = $offersSpecialResolved;
            }

            if ($offersRecommendationSaved != $offersRecommendationResolved) {
                $_SESSION['BASKET_RECOMMENDATION_OFFERS'] = $offersRecommendationResolved;
            }
        }

        $res = parent::applyDiscount($data);

        $this->calcDiscountProps();

        return $res;
    }

    /**
     * @return Basket
     */
    public function getBasket()
    {
        return parent::getBasket();
    }

    function getDeliveryDateTimeValue()
    {
        return DateTime::parseFromDateTime($this->getDeliveryDateTimeFull());
    }

    function getDeliveryDateTimeFull()
    {
        return $this->getDeliveryDateTime() . ':00';
    }

    function getDeliveryDateTime()
    {
        $sDate = $this->getPropVal('DATE');
        $sTime = $this->getPropVal('TIME');
        if ($sDate && $sTime) {
            $sDateTime = $sDate . ' ' . $sTime;
            return trim($sDateTime);
        }
    }

    public function getCoordinates()
    {
        return GeoCoordinates::createIfExists($this->getPropVal('HOUSE_COORDS') ?: $this->getPropVal('STREET_COORDS'));
    }

    var $parsedAddress;

    public function getParsedAddress()
    {
        if (!isset($this->parsedAddress)) {
            $address = trim($this->getPropVal('ADDRESS'));
            $this->parsedAddress = $this->container->getGeoService()->parseAddress($address);
        }
        return $this->parsedAddress;
    }

    public function getCity()
    {
        $city = trim($this->getPropVal('CITY'));
        if (!$city) {
            $parsed = $this->getParsedAddress();
            $city = $parsed['city_format'];
        }
        return $city;
    }

    public function getStreet()
    {
        $street = trim($this->getPropVal('STREET'));
        if (!$street) {

        }
        return $street;
    }

    public function getAddressFor1c($withFlat = false)
    {
        $parts = preg_split('/\s*\,\s*/', $this->getAddressFull($withFlat));
        $result = [];
        foreach ($parts as $part) {
            if (!preg_match('/(Иркутская обл)/', $part)) {
                $result[] = $part;
            }
        }
        return join(', ', $result);
    }

    public function getAddressFiasId()
    {
        return $this->getPropVal('HOUSE_FIAS_ID') ?: $this->getPropVal('STREET_FIAS_ID');
    }

    function getServiceDepartmentId()
    {
        $office = $this->getDepartment();
        if ($office) {
            return $office['PROPERTY_SERVICE_ID_VALUE'];
        }
    }

    function setDeliveryDepartmentId($officeId)
    {
        return $this->setPropVal('DELIVERY_DEPARTMENT', $officeId);
    }

    function setPropVal($code, $val)
    {
        return $this->setPropValue('CODE', $code, $val);
    }

    function getPropVal($code, $def = null)
    {
        return $this->getPropValue('CODE', $code, $def);
    }

    function getAddressFull($withFlat = false)
    {
        $list = [];
        if ($v = $this->getPropVal('ADDRESS')) {
            $list[] = $v;
        }
        if ($withFlat && $this->getPropVal('PRIVATE_HOUSE') && $this->getPropVal('FLAT')) {
            $list[] = 'кв ' . $this->getPropVal('FLAT');
        }
        return join(', ', $list);
    }

    function getAddressLocation()
    {
        $list = [];
        if ($v = $this->getPropVal('ADDRESS')) {
            $list[] = $v;
        }
        return join(', ', $list);
    }

    function getDeliveryDataHash()
    {
        $parts = [];

        $parts[] = $this->getTransportType();
        $parts[] = $this->getField('DELIVERY_ID');

        if ($this->getTransportType() === 'courier') {
            $parts[] = $this->getAddressFull();
        } else {
            $parts[] = $this->getPropVal('PICKUP_DEPARTMENT');
        }

        return join('_', $parts);
    }

    function getServicePayType()
    {
        $wrapper = $this->getPayTypeWrapper();
        if ($wrapper->isOnline()) {
            return 2;
        } else if ($wrapper->isTerminal()) {
            return 1;
        } else {
            return 0;
        }
    }

    function canUseBenefitType($type)
    {
        return $this->container->getBenefitService()->checkOrderBenefitType($this, $type);
    }

    function getPhone()
    {
        return $this->getPropVal('PHONE');
    }

    public function getBonuses()
    {
        $sum = $this->getPropVal('BONUSES', 0);
        $sum = intval($sum);
        return $sum;
    }

    function getBonusesAvailable()
    {
        $userId = $this->container->getUserId();
        return $userId ? $this->container->getSaleClientCardService()->getUserBonuses($userId) : 0;
    }

    public function getBonusesUseChecked()
    {
        $bonuses = $this->getBonuses();
        $bonusesAvailable = $this->getBonusesAvailable();
        if ($bonuses) {
            if ($bonuses > $bonusesAvailable) {
                return $bonusesAvailable;
            } else {
                return $bonuses;
            }
        }
        return 0;
    }

    function bonusesDelete()
    {
        $this->setPropVal('BONUSES', 0);
        return true;
    }

    function setBenefitType($type = null)
    {
        if (!$type) {
            $availableBenefits = $this->container->getBenefitService()->getOrderAvailableBenefits($this);
            $type = array_keys($availableBenefits)[0];
        }
        $this->setPropVal('BENEFIT_TYPE', $type);
    }

    function getBenefitType()
    {
        return $this->getPropVal('BENEFIT_TYPE');
    }

    var $couponsChanged = false;

    function isCouponsChanged($changed = null)
    {
        if (isset($changed)) {
            $this->couponsChanged = $changed;
        }
        return $this->couponsChanged;
    }

    function couponDelete($couponCode)
    {
        $this->setPropVal('PROMOCODE', '');
        $this->isCouponsChanged(true);
        $this->setBenefitType();
        return true;
    }

    function couponsDelete()
    {
        $this->setPropVal('PROMOCODE', '');
        $this->isCouponsChanged(true);
        $this->setBenefitType();
        return true;
    }

    function couponAdd(Coupon $coupon)
    {
        $this->setPropVal('PROMOCODE', $coupon['ID'] . ':' . $coupon->getCouponCode());
        $this->isCouponsChanged(true);
        $this->setBenefitType('promocode');
        return true;
    }

    function couponApply($couponCode)
    {
        $service = $this->container->get1СService();
        $phone = $this->getPhone();
        $price = $this->getTotalField('PRICE_BASKET');

        $couponModel = $this->container->getCouponService()->ensurePromocode($couponCode);

        if ($couponModel) {
            if ($minPrice = $couponModel->getProp('MIN_PRICE')) {
                if ($price < $minPrice) {
                    return 'Минимальная сумма заказа для промокода ' . $minPrice;
                } else {
                    $this->couponAdd($couponModel);
                    return true;
                }
            } else {
                $this->couponAdd($couponModel);
                return true;
            }
        } else {
            return false;
        }
    }

    function getCancelReasons()
    {
        return [
            [
                'CODE' => 'time',
                'NAME' => 'Слишком долгое ожидание'
            ],
            [
                'CODE' => 'courier_time',
                'NAME' => 'Курер опаздывает'
            ],
            [
                'CODE' => 'changed_mind',
                'NAME' => 'Передумали заказывать'
            ],
            [
                'CODE' => 'other',
                'NAME' => 'Другая причина'
            ],
        ];
    }

    function getStatusRelatedFields()
    {
        $data = [
            'ID' => $this->getId(),
            'CANCEL_REASONS' => $this->getCancelReasons(),
            'COURIER_STATE' => null,
            'STATUS' => (array)$this->getStatusInfo(),
            'STATUS_ID' => $this->getField('STATUS_ID'),
            'STATUS_NAME' => $this->getStatusName(),
            'STATUS_COLOR' => $this->getStatusColor(),
            'CSTATUS_ID' => $this->getComputedStatusId(),
            'CSTATUS_NAME' => $this->getComputedStatusName(),
            'CSTATUS_COLOR' => $this->getComputedStatusColor(),
            'IS_PAID' => $this->isPaid(),
            'IS_CAN_PAY' => $this->isCanPay(),
            'IS_CAN_PAY_ONLINE' => $this->isCanPayOnline(),
            'IS_FINISHED' => $this->isFinished(),
            'IS_ACTIVE' => $this->isActive(),
            'IS_CAN_CANCEL' => false,
            'IS_CANCELED' => $this->isCanceled(),
        ];
        return $data;
    }


    /**
     * @return OrderAttributeCollection
     */
    public function getAttributes($refetch = false)
    {
        if (!isset($this->attributes) || $refetch) {

            $attrsService = $this->container->getOrderAttributesService();

            $attrs = new OrderAttributeCollection([]);

            /* @var $props OrderPropertyValue[] */
            $this->getProps();

            $propsByCode = $this->propsByCode;

            foreach ($propsByCode as $code => $propValue) {
                $attr = $attrsService->getAttribute($code);
                $attr->setValueEntity($attr->createValueEntity($propValue->getValue()));
                $attrs[$code] = $attr;
            }

            $fields = [];

            $fields['DELIVERY_ID'] = [
                'VALUE' => intval($this->getDeliveryId()),
                'VALUE_VIEW' => $this->getDeliveryName(),
            ];

            $fields['PAY_SYSTEM_ID'] = [
                'VALUE' => intval($this->getPaymentSystemId()),
                'VALUE_VIEW' => $this->getPaysystemName()
            ];

            $fields['USER_DESCRIPTION'] = [
                'VALUE' => $this->getField('USER_DESCRIPTION'),
                'VALUE_VIEW' => $this->getField('USER_DESCRIPTION'),
            ];

            foreach ($fields as $fieldCode => $field) {
                $attr = $attrsService->getAttribute($fieldCode);
                if ($attr) {
                    $attr->setValueEntity($attr->createValueEntity($field['VALUE']));
                    $attrs[$fieldCode] = $attr;
                }
            }

            $this->attributes = $attrs;
        }

        return $this->attributes;
    }


    /* @var OrderAttributeCollection */
    public $clientAttributes;

    public function getClientAttributes()
    {
        if (!isset($this->clientAttributes)) {
            $order = $this;
            $order->getProps();
            $attrs = $this->getAttributes();
            $this->clientAttributes = $attrs;
        }
        return $this->clientAttributes;
    }
}
