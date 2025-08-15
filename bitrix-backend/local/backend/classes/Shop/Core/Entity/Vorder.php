<?php

namespace Shop\Core\Entity;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Type\DateTime;
use Company\Core\Entity\Office;
use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\Model\ModelCollection;
use Main\Entity\User\UserModel;
use Shop\Core\Lib\IOrderAttribute;
use TG\Main\Helper;
use function TG\Shop\Core\Entity\FormatDate;
use const TG\Shop\Core\Entity\SITE_ID;

class Vorder extends D7Model
{
    use Containerable;

    public $jsonFields = [
        'UF_PROPS' => [],
        'UF_FIELDS' => [],
        'UF_BASKET' => ['isArray' => true],
        'UF_SPECIAL_OFFERS' => ['isArray' => true],
    ];

    /* @var OrderModel */
    public $order;

    public $basketItems = [];
    public $user;

    public $contextCurrent = false;
    public $couponsCache = null;
    public $prevOrder;

    public $saveTimes = 0;
    public $result;

    function __construct($id = null, $fields = null)
    {
        parent::__construct($id, $fields);
    }

    public static function tableName()
    {
        return 'vorder_state';
    }

    public static function query()
    {
        return new VorderQuery(static::instantiateAdapter(), get_called_class(), ModelCollection::class);
    }

    function getDeliveryInitId()
    {
        return $this->getOrderField('DELIVERY_ID', 1);
    }

    function save($selectedFields = [])
    {
        $this->saveTimes++;

        $order = $this->getOrder();

        $this['UF_FIELDS'] = $order->getVorderFields() + ($this['UF_FIELDS'] ?? []);
        $this['UF_PROPS'] = $order->getPropsValuesMap() + ($this['UF_PROPS'] ?? []);
        $this['UF_PRICE'] = $order->getPriceTotal();

        $this['UF_BASKET'] = $this->getBasketJson();

        if (!$this['UF_DATE_INSERT']) {
            $this['UF_DATE_INSERT'] = FormatDate('FULL', time());
        }

        $this['UF_DATE_UPDATE'] = FormatDate('FULL', time());

        return parent::save($selectedFields);
    }

    function createOrder($editMode = false)
    {
        $this->container->getGraphqlService()->timerCollectorStart('createOrder');
        $userId = $this->getUserId();

        if ($this['UF_ORDER_ID']) {
            $order = OrderModel::load($this['UF_ORDER_ID']);
            if ($userId && ($userId != $this->getUserId()))
                $order->setFieldNoDemand('USER_ID', $userId);
        } else {
            $this->container->getGraphqlService()->timerCollectorStart('createOrderElse');
            $order = OrderModel::create(Bitrix\Main\Context::getCurrent()->getSite(), $userId);
            $currentDateTime = new DateTime();
            $order->setField('DATE_INSERT', $currentDateTime);
            $order->setField('DATE_UPDATE', $currentDateTime);
            $order->setPersonTypeIdExt($this->getPersonTypeId());
            $this->container->getGraphqlService()->timerCollectorEnd('createOrderElse');
        }

        if (!$order->getId() || $editMode) {
            $order->overridePropsValues($this->getSavedPropsForCreate());
        }

        $order->initCouponsData();
        $order->setPropVal('VORDER_ID', $this['ID']);
        $this->container->getGraphqlService()->timerCollectorEnd('createOrder');
        return $order;
    }

    function getOrder($refetch = false)
    {
        if (!$this->order || $refetch) {
            $this->order = $this->createOrder();
            $this->loadBasket($refetch);
        }
        return $this->order;
    }

    function loadBasket($refetch = false)
    {
        $this->container->getGraphqlService()->timerCollectorStart('loadBasket');
        $this->order->setMathActionOnly(true);
        $this->order->setBasket($this->createBasket($this->order, $refetch));
        $this->order->setMathActionOnly(false);
        $this->container->getGraphqlService()->timerCollectorEnd('loadBasket');
    }

    function getBasket()
    {
        return $this->getOrder()->getBasket();
    }

    function getBasketItems()
    {
        return $this['UF_BASKET'];
    }

    function getBasketJson()
    {
        $result = [];
        foreach ($this->getBasket() as $item) {
            $result[] = $item->getFields()->getValues();
        }
        return $result;
    }

    function getUserId()
    {
        if ($this['UF_USER_ID']) {
            return $this['UF_USER_ID'];
        } else if ($this->order) {
            return $this->order->getField('USER_ID');
        }
    }

    function createBasket(OrderModel $order, $refetch = false)
    {
        $siteId = SITE_ID;

        if ($order->getId()) {
            $basket = $order->getBasket();
        } else {
            if ($this->getScope() === 'bitrix') {
                if ($this->isContextCurrent()) {
                    $basket = $this->container->getBasketService()->getBasket($refetch);
                } else if ($this['UF_FUSER_ID']) {
                    $basket = Basket::loadItemsForFUser($this['UF_FUSER_ID'], $siteId);
                }
            } else {
                $basket = \Bitrix\Sale\Basket::create($siteId);
                $currencyCode = CurrencyManager::getBaseCurrency();
                foreach ($this->basketItems as $basketItem) {
                    $item = $basket->createItem('catalog', $basketItem['PRODUCT_ID']);
                    $item->setFields(array(
                        'QUANTITY' => $basketItem['QUANTITY'],
                        'CURRENCY' => $currencyCode,
                        'LID' => $siteId,
                        'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
                    ));
                }
            }
        }

        return $basket;
    }

    function getScope()
    {
        return $this['UF_SCOPE'];
    }

    function getPersonTypeId()
    {
        return $this->getOrderField('PERSON_TYPE_ID');
    }

    function getOrderField($name, $def = null)
    {
        $orderValue = $this->order ? $this->order->getField($name) : null;
        return $orderValue ?? $this->fields['UF_FIELDS'][$name] ?? $def;
    }

    function setUserId($userId)
    {
        $this['UF_USER_ID'] = $userId;
        if ($this->order) {
            $this->order->setFieldNoDemand('USER_ID', $userId);
        }
        $this->user = null;
        return $this;
    }

    function getUser($refetch = false)
    {
        if (!isset($this->user) || $refetch) {
            $userId = $this->getUserId();
            $this->user = $userId ? UserModel::instance($userId) : false;
        }
        return $this->user;
    }

    function isContextCurrent()
    {
        return $this->contextCurrent;
    }

    function setContextCurrent()
    {
        return $this->contextCurrent = true;
    }

    function reloadBasket()
    {
        $this->loadBasket(true);
        $this->order->createOrderShipment($this->order->getField('DELIVERY_ID'));
    }

    function getSavedPropsForCreate()
    {
        $data = $this->fields['UF_PROPS'];

        if ($data['DATE']) {
            $date = $data['DATE'];
            if ($data['TIME']) {
                $date .= ' ' . $data['TIME'];
            } else {
                $date .= ' 23:59';
            }
            $dateUnix = strtotime($date);
            if (time() > $dateUnix) {
                unset($data['DATE']);
                unset($data['TIME']);
                unset($data['RESERVE_SUCCESS_REQUEST_TIME']);
            }
        }

        return $data;
    }

    function getProps($refetch = false)
    {
        return $this->getOrder()->getProps($refetch);
    }

    function isNew()
    {
        return !$this['ID'];
    }

    function setPropValue($by, $val, $propValue, $sessionSave = true)
    {
        $this->getOrder()->setPropValue($by, $val, $propValue);
        return $this;
    }

    function setPropVal($code, $value)
    {
        $this->getOrder()->setPropVal($code, $value);
        return $this;
    }

    function getPropVal($code, $def = null)
    {
        return $this->getOrder()->getPropVal($code, $def);
    }

    function getPropValue($by, $propName, $def = null)
    {
        return $this->getOrder()->getPropValue($by, $propName, $def);
    }

    function getProp($by, $val)
    {
        return $this->getOrder()->getProp($by, $val);
    }

    function getPaysystemId()
    {
        return $this->getOrderField('PAY_SYSTEM_ID');
    }

    function setProfileId($profileId)
    {
        if (is_numeric($profileId))
            $profileId = intval($profileId);
        $this->setPropVal('PROFILE_ID', $profileId);
        return $this;
    }

    function getProfileId()
    {
        return $this->getPropVal('PROFILE_ID');
    }

    function refetchProps()
    {
        $this->getOrder()->resetTotals();
    }

    function isForceDeliveryFree()
    {
        return false;
    }

    function setDepartmentId($id)
    {
        if (!$this->isSelfPickup()) {
            $this->setPropVal('DELIVERY_DEPARTMENT', $id);
        } else {
            $this->setPropVal('PICKUP_DEPARTMENT', $id);
        }
    }

    function isSelfPickup()
    {
        $wrapper = $this->getOrder()->getDeliveryWrapper();
        return $wrapper ? ($wrapper->getTransportType() === 'pickup') : false;
    }

    function getPickupDepartmentId()
    {
        return $this->getPropVal('PICKUP_DEPARTMENT');
    }

    function getDeliveryDateTimeUnix()
    {
        return strtotime($this->getDeliveryDateTimeFull());
    }

    function getDeliveryDateTimeFull()
    {
        return $this->getDeliveryDateTime() . ':00';
    }

    function getDeliveryDateTime()
    {
        $sDate = $this->getPropVal('DATE');
        $sTime = $this->getPropVal('TIME');
        $sDateTime = $sDate . ' ' . $sTime;
        return trim($sDateTime);
    }

    function getDepartmentId()
    {
        if (!$this->isSelfPickup()) {
            return $this->getDeliveryDepartmentId();
        } else {
            return $this->getPickupDepartmentId();
        }
    }

    function getDeliveryDepartmentId()
    {
        return $this->getPropVal('DELIVERY_DEPARTMENT');
    }

    function getDepartment()
    {
        $departmentId = $this->getDepartmentId();
        return $departmentId ? Office::query()->withViewList()->getById($departmentId) : null;
    }

    function getPhone()
    {
        return $this->getPropVal('PHONE');
    }

    function getEmail()
    {
        return $this->getPropVal('EMAIL');
    }

    function getPhoneSaved()
    {
        return \Main\Helper\Format::validateMobile($this->getSavedProp('PHONE'));
    }

    function getEmailSaved()
    {
        return $this->getSavedProp('EMAIL');
    }

    function getSavedProp($propId, $def = null)
    {
        return $this->fields['UF_PROPS'][$propId] ?? $def;
    }

    /**
     * @return IOrderAttribute[]
     */
    public function getAttributes()
    {
        $order = $this->getOrder();
        $attributes = $order->getAttributes();
        return $attributes;
    }

    /* @var OrderAttributeCollection */
    public $clientAttributes;

    function getClientAttributes()
    {
        if (!isset($this->clientAttributes)) {
            $attrs = $this->getAttributes();
            $this->clientAttributes = $attrs;
        }
        return $this->clientAttributes;
    }

    function getSummary()
    {
        $session = static::container()->getSession();
        return [
            'ID' => $this->getId(),
            'PHONE' => $this->getPhoneSaved(),
            'EMAIL' => $this->getEmailSaved(),
            'FUSER_ID' => $session->getFuserId()
        ];
    }

    function getServiceDepartmentId()
    {
        $office = $this->getDepartment();
        if ($office) {
            return $office['PROPERTY_SERVICE_ID_VALUE'];
        }
    }

    function getBonuses()
    {
        $sum = $this->getPropVal('BONUSES', 0);
        $sum = intval($sum);
        return $sum;
    }

    function setOrderField($name, $value)
    {
        if ($this->order) {
            $this->order->setVorderField($name, $value);
        }
        $this->fields['UF_FIELDS'][$name] = $value;
    }

    function getId()
    {
        return intval($this->fields['ID'] ?? $this->id);
    }

    function setDeliveryId($typeId = 0)
    {
        $typeId = $typeId ?: 2;
        $this->setOrderField('DELIVERY_ID', $typeId);
    }

    function setPaysystemId($id)
    {
        $this->setOrderField('PAY_SYSTEM_ID', $id);
    }

    function getCoupons()
    {
        return $this->getOrder()->getCoupons();
    }

    function basketSave()
    {
        $order = $this->getBasket()->getOrder();
        $this->getBasket()->setOrderEmpty();
        $this->getBasket()->save();
        $this->getBasket()->setOrder($order);
    }

    function getClientCard()
    {
        return $this->container->getSaleClientCardService()->getCurrentUserCard();
    }

    function getDiscounts()
    {
        $card = $this->getClientCard();
        $discounts = $this->container->getDiscountService()->getDiscounts();
        $discounts = $card ? $discounts->filterByClientCard($card) : $discounts->filterGuest();
        return $discounts;
    }

    function getBasketRules()
    {
        $discounts = $this->getDiscounts();

        $rules = [];

        /** @var ElementModel $discount */
        foreach ($discounts as $discount) {

            $ruleConditions = [];
            $ruleActions = [];

            $ruleAction = [
                'TYPE' => 'discount',
                'MODE' => 'percent',
                'AMOUNT' => $discount->getProp('ACTION_DISCOUNT_PERCENT'),
            ];

            $productIds = $discount->getProp('ACTION_PRODUCT_IDS', 'VALUE', true);
            $productSectionIds = $discount->getProp('ACTION_SECTION_IDS', 'VALUE', true);

            if (empty($productIds) && empty($productSectionIds)) {
                $ruleAction['TARGET'] = 'total';
            } else {
                $ruleAction['TARGET'] = 'product';
                $ruleAction['PRODUCT_IDS'] = \Main\Helper\Format::getJsValueDeep($productIds);
                $ruleAction['SECTION_IDS'] = \Main\Helper\Format::getJsValueDeep($productSectionIds);
            }

            $ruleActions[] = $ruleAction;

            $conditions = $discount->getComplexProp('CONDITION');

            foreach ($conditions as $condition) {

                $ruleCondition = [
                    'TYPE' => 'group_and',
                    'CHILDREN' => [],
                ];

                if (!empty($condition['TRANSPORT_TYPE'])) {
                    $ruleCondition['CHILDREN'][] = [
                        'TYPE' => 'transport_type',
                        'VALUE' => $condition['TRANSPORT_TYPE']
                    ];
                }
                if (!empty($condition['ORDER_PRICE_FROM'])) {
                    $ruleCondition['CHILDREN'][] = [
                        'TYPE' => 'order_price',
                        'MIN' => $condition['ORDER_PRICE_FROM'],
                        'MAX' => $condition['ORDER_PRICE_TO'],
                    ];
                }
                if (!empty($condition['PAYSYSTEM_ID'])) {
                    $ruleCondition['CHILDREN'][] = [
                        'TYPE' => 'paysystem_id',
                        'VALUE' => $condition['PAYSYSTEM_ID'],
                    ];
                }
                if (!empty($condition['PAYMENT_TYPE'])) {
                    $ruleCondition['CHILDREN'][] = [
                        'TYPE' => 'payment_type',
                        'VALUE' => $condition['PAYMENT_TYPE'],
                    ];
                }

                $ruleConditions[] = $ruleCondition;
            }

            $rule = [
                'ID' => $discount['ID'],
                'CODE' => $discount['CODE'] ?? $discount['ID'],
                'NAME' => $discount['NAME'],
                'CONDITIONS' => $ruleConditions,
                'ACTIONS' => $ruleActions,
            ];

            $rules[] = $rule;
        }

        return $rules;
    }
}
