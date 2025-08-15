<?php

namespace Shop\Core\Entity;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Event;
use Bitrix\Sale\Delivery;
use Company\Core\Entity\Office;
use Main\DI\Containerable;
use Main\Entity\User\UserModel;
use Main\Model\Result;
use Shop\Core\Error\VOrderValidateError;
use TG\Main\Helper;
use function TG\Shop\Core\Entity\randString;
use const TG\Shop\Core\Entity\SITE_ID;

class VorderCurrent extends Vorder
{
    use Containerable;

    const PAYSYSTEM_NO_EMPTY = false;
    const DELIVERY_NO_EMPTY = false;

    static $instance;
    static $instanceByScope = [];

    public $personTypes = [];

    public $computedDeliveries;
    public $computedPaysystems;

    public $contextCurrent = false;
    public $couponsCache = null;
    public $prevOrder;

    public $saveTimes = 0;
    public $result;

    static function getGuestOrdersIds($scope = 'bitrix', $filter = [], $limit = 10)
    {
        $session = static::container()->getSession();

        $fuserId = $session->getFuserId();
        $sessionId = $session->getSessionId();

        $filterPerm = [
            'LOGIC' => 'OR'
        ];

        if ($fuserId) {
            $filterPerm['=UF_FUSER_ID'] = $fuserId;
        }

        if ($sessionId) {
            $filterPerm['=UF_SESSION_ID'] = $sessionId;
        }

        $filter = [
            'LOGIC' => 'AND',
            '=UF_SCOPE' => $scope,
            '!UF_ORDER_ID' => 0,
            $filterPerm
        ];

        $orderIds = static::query()
            ->filter($filter)
            ->select(['ID', 'UF_ORDER_ID'])
            ->sort('ID', 'DESC')
            ->limit($limit)
            ->getList()
            ->pluckArray('UF_ORDER_ID', 'UF_ORDER_ID');

        return array_filter($orderIds);
    }

    static function getCurrentId()
    {
        $model = static::getCurrent();
        return $model['ID'];
    }

    static function getCurrent($scope = 'bitrix', $new = false)
    {
        if (!isset(static::$instanceByScope[$scope]) || $new) {

            static::$instanceByScope[$scope] = false;

            $session = static::container()->getSession();

            $fuserId = $session->getFuserId();
            $userId = $session->getUserId();
            $sessionId = $session->getSessionId();

            if (!$new) {

                $filterPerm = [
                    'LOGIC' => 'OR'
                ];

                if ($fuserId) {
                    $filterPerm['=UF_FUSER_ID'] = $fuserId;
                }

                if ($sessionId) {
                    $filterPerm['=UF_SESSION_ID'] = $sessionId;
                }

                if ($userId) {
                    $filterPerm['UF_USER_ID'] = $userId;
                }

                $filter = [
                    'LOGIC' => 'AND',
                    '=UF_SCOPE' => $scope,
                    'UF_ORDER_ID' => 0,
                    $filterPerm
                ];

                self::container()->getGraphqlService()->timerCollectorStart('vorderFind');

                $model = static::query()->filter($filter)->sort('ID', 'DESC')->first();

                if ($userId) {

                    if ($model['UF_USER_ID'] && ($model['UF_USER_ID'] != $userId)) {
                        $filter = [
                            'LOGIC' => 'AND',
                            '=UF_SCOPE' => $scope,
                            'UF_ORDER_ID' => 0,
                            'UF_USER_ID' => $userId,
                        ];
                        $model = static::query()->filter($filter)->sort('ID', 'DESC')->first();
                    }
                }

                self::container()->getGraphqlService()->timerCollectorEnd('vorderFind');
            }

            if (!$model) {
                $model = static::getCurrentNew($scope);
                $model->initVorder();
                $model->ensureOrder();
                $model->save();
            } else {
                $model['UF_USER_ID'] = $userId;
                $model->setContextCurrent();
                $model->initVorder();
                $model->ensureOrder();
            }

            static::$instanceByScope[$scope] = $model;
        }
        return static::$instanceByScope[$scope];
    }

    static function getCurrentNew($scope = 'bitrix')
    {
        $session = static::container()->getSession();

        $fuserId = $session->getFuserId();
        $userId = $session->getUserId();
        $sessionId = $session->getSessionId();

        $model = new static(null, [
            'UF_SCOPE' => $scope ?: '',
            'UF_FUSER_ID' => $fuserId ?: 0,
            'UF_SESSION_ID' => $sessionId ?: '',
            'UF_USER_ID' => $userId ?: 0,
        ]);

        $model->setContextCurrent();

        return $model;
    }

    function initVorder()
    {
        $this->container->getGraphqlService()->timerCollectorStart('initVorder');
        if ($this->isContextCurrent()) {
            if (!$this['UF_USER_ID']) {
                $currentUserId = $this->container->getUserId();
                if ($currentUserId) {
                    $this->setUserId($currentUserId);
                    parent::save();
                }
            }
        }
        $this->getOrder();
        $this->getProps();
        $this->prepareVirtualOrder();
        $this->container->getGraphqlService()->timerCollectorEnd('initVorder');
    }

    function prepareVirtualOrder($refetch = false)
    {
        $this->container->getGraphqlService()->timerCollectorStart('prepareVirtualOrder');

        $order = $this->getOrder($refetch);

        if ($order->getId())
            return;

        $order->setFieldNoDemand('PERSON_TYPE_ID', $this->getPersonTypeId());

        if ($this->getPaysystemId())
            $order->setFieldNoDemand('PAY_SYSTEM_ID', $this->getPaysystemId());

        if ($this->getDeliveryInitId())
            $order->setFieldNoDemand('DELIVERY_ID', $this->getDeliveryInitId());

        $order->setFieldNoDemand('USER_DESCRIPTION', $this->getOrderField('USER_DESCRIPTION'));

        $order->createOrderShipment($order->getField('DELIVERY_ID'));

        if ($this->getPaysystemId()) {
            $order->createOrderPayment($order->getField('PAY_SYSTEM_ID'));
        }

        $this->container->getGraphqlService()->timerCollectorEnd('prepareVirtualOrder');

        return $order;
    }

    function getPersonTypeId()
    {
        $personTypeId = parent::getPersonTypeId();
        if (!$personTypeId || !$this->getPersonTypeById($personTypeId)) {
            $personType = $this->getPersonTypes();
            if (!empty($personType)) {
                $personTypeId = array_keys($personType)[0];
            }
        }
        return $personTypeId;
    }

    function getPersonTypeById($id)
    {
        $this->getPersonTypes();
        return $this->personTypes[$id];
    }

    /**
     * @return array
     */
    function getPersonTypes(): array
    {
        if (empty($this->personTypes)) {
            $this->personTypes = PersonType::getListAllById();
        }
        return $this->personTypes;
    }

    function getPaysystemId()
    {
        $paysystemId = parent::getPaysystemId();
        $activePaysystems = null;

        if ($paysystemId) {
            $activePaysystems = $this->getComputedPaysystems();
            if ($activePaysystems[$paysystemId]) {
                $paysystem = $activePaysystems[$paysystemId];
            }
        }

        if (!$paysystem && static::PAYSYSTEM_NO_EMPTY) {
            if (!isset($activePaysystems))
                $activePaysystems = $this->getComputedPaysystems();

            $paysystem = current($activePaysystems);
        }

        if ($paysystem) {
            return $paysystem['ID'];
        }
    }

    function getComputedPaysystems($refetch = false)
    {
        if (!isset($this->computedPaysystems) || $refetch) {
            $this->computedPaysystems = $this->container->getPaymentService()->getComputedPaysystems($this->getOrder());
        }
        return $this->computedPaysystems;
    }

    function ensureOrder()
    {
        $user = $this->container->getUser();
        $isNew = $this->isNew();

        if ($isNew) {

            $this->setPropVal('hash', $this->genHash());
            $this->setPropVal('SERVICE_SEND', $this->container->getConfigService()->get('SERVICE_1C.SEND_ORDER', true) ? 'Y' : 'N');

            \Bitrix\Main\Loader::includeModule('sale');

            /*
            \Bitrix\Sale\DiscountCouponsManager::init();
            \Bitrix\Sale\DiscountCouponsManager::clear(true);
            \Bitrix\Sale\DiscountCouponsManager::clearApply(true);
            */

            if ($user) {

            } else {
                if ($prevOrder = $this->findPrevOrder()) {

                    $propsProfiled = $prevOrder->getPropsProfiled();

                    foreach ($propsProfiled as $prop) {
                        $propValue = $prop->getField('VALUE');
                        $propCode = $prop->getField('CODE');
                        if ($propValue) {
                            $this->setPropVal($propCode, $propValue);
                        }
                    }

                    $propCodes = [
                        'PHONE',
                        'FIO',
                        'EMAIL'
                    ];

                    foreach ($propCodes as $propCode) {
                        $propValue = $prevOrder->getPropVal($propCode);
                        if ($propValue) {
                            $this->setPropVal($propCode, $propValue);
                        }
                    }
                }
            }

            $this->check(false);
            $this->refetchProps();
        }

        return $this;
    }

    function genHash()
    {
        $length = 3; // длинна строковой части кодового слова
        $nLength = 5; // длинна числовой части кодового слова
        $symbols = [ // символы
            "ABCDEFGHIJKLNMOPQRSTUVWXYZ"
        ];
        $numbers = [
            "0123456789"
        ];
        $hash = randString($length, $symbols) . randString($nLength, $numbers);
        return $hash;
    }

    /**
     * @return OrderModel
     */
    function findPrevOrder()
    {
        if (!isset($this->prevOrder)) {

            $userId = $this->getUserId();

            if ($userId) {
                $order = OrderModel::query()->filter(['USER_ID' => $userId])->sort('ID', 'DESC')->first();
            } else {
                $session = static::container()->getSession();

                $fuserId = $session->getFuserId();
                $sessionId = $session->getSessionId();

                $filter = [
                    '!ID' => $this['ID'],
                    'UF_SCOPE' => $this['UF_SCOPE'],
                    '>UF_ORDER_ID' => 0,
                    [
                        'LOGIC' => 'OR',
                        'UF_FUSER_ID' => $fuserId,
                        'UF_SESSION_ID' => $sessionId,
                    ]
                ];

                $vorder = static::query()->filter($filter)->sort('ID', 'DESC')->first();

                if ($vorder) {
                    $order = OrderModel::query()->getById($vorder['UF_ORDER_ID']);
                }
            }

            $this->prevOrder = $order ?? false;
        }
        return $this->prevOrder;
    }

    function check($save = false)
    {
        $changed = false;
        $changedBasket = false;

        $basket = $this->getBasket();

        /*
        $orderableItems = $basket->getOrderableItems();

        $orderableItemsIndexed = [];

        foreach ($orderableItems as $item) {
            $orderableItemsIndexed[$item->getField('ID')] = $item;
        }

        foreach ($basket as $item) {
            if (!$orderableItemsIndexed[$item->getField('ID')]) {
                $item->delete();
                $changedBasket = true;
            }
        }
        */

        if ($this->getUser()) {

            if ($this->checkUserProps())
                $changed = true;

            if ($this->checkUserProfile())
                $changed = true;

            if ($this->checkUserBonuses())
                $changed = true;
        }

        if ($changedBasket) {
            $basket->save();
            $this->getProps(true);
            $this->prepareVirtualOrder(true);
            $changed = true;
        }

        if ($changed && $save) {
            $this->save();
        }

        return $changed;
    }

    function checkUserProps()
    {
        $schema = $this->getPropsSchema();

        foreach ($schema as $propCode => $propInfo) {

            $orderPropVal = $this->getPropVal($propCode);
            $userPropVal = null;

            if (!$orderPropVal || $propInfo['readonly']) {

                if (!empty($propInfo['value'])) {
                    if (is_callable($propInfo['value'])) {
                        $userPropVal = $propInfo['value']();
                    }
                }

                if ($userPropVal) {
                    if (!$orderPropVal || $propInfo['readonly'] && ($userPropVal != $orderPropVal)) {
                        $this->setPropVal($propCode, $userPropVal);
                        $changed = true;
                    }
                }
            }
        }

        return $changed;
    }

    function getPropsSchema()
    {
        $user = $this->getUser();

        $result = [];

        if ($user) {
            $result += [
                'FIO' => [
                    'value' => [$user, 'getNameFirstLast'],
                    'readonly' => true,
                ],
                'PHONE' => [
                    'value' => [$user, 'getPhone'],
                    'readonly' => true
                ],
                'EMAIL' => [
                    'value' => [$user, 'getEmailReal'],
                    'readonly' => true
                ],
            ];
        }

        return $result;
    }

    function checkUserProfile($newProfileId = null, $forceReload = false)
    {
        $order = $this->getOrder();

        if ($this->isNew()) {

            $lastOrder = $this->findPrevOrder();

            if ($lastOrder) {
                $lastOrderProfileId = $lastOrder->getPropVal('PROFILE_ID');
            }

            if ($lastOrderProfileId) {
                $defaultProfile = $this->getUserProfileDefault(false);
                if ($defaultProfile) {
                    $profileId = $defaultProfile['ID'];
                } else {
                    $profile = $this->getUserProfile($lastOrderProfileId, true, true);
                    if ($profile) {
                        $profileId = $profile['ID'];
                    }
                }
            } else {
                $profile = $this->getUserProfile($lastOrderProfileId, true, true);
                if ($profile) {
                    $profileId = $profile['ID'];
                }
            }


            if ($profileId) {
                $this->setProfileId($profileId);
                $this->getOrder()->loadProfileProps($profileId);
            }

            $changed = true;

        } else {
            $profileIdSaved = $this->getProfileId();
            $profileIdObtained = $this->getUserProfileIdObtained($newProfileId);

            if ($profileIdSaved !== $profileIdObtained || $forceReload) {
                if ($profileIdObtained !== 'custom') {
                    $order->loadProfileProps($profileIdObtained);
                }
                $changed = true;
                $this->setProfileId($profileIdObtained);
            }
        }

        return $changed;
    }

    function getUserProfileDefault($loadLast = true)
    {
        $user = $this->getUser();

        if (!$user) return;

        $personTypeId = $this->getPersonTypeId();
        $profiles = $this->getUserProfiles();
        $profiles = array_values($profiles);

        $personType = $this->getPersonType();
        $foundProfile = null;

        foreach ($profiles as $profile) {
            if ($profile['PERSON_TYPE_ID'] == $personTypeId) {
                if ($profile->isDefault()) {
                    $foundProfile = $profile;
                    break;
                } else if (!$foundProfile) {
                    $foundProfile = $profile;
                }
            }
        }

        return $foundProfile;
    }

    function getUserProfiles()
    {
        $userId = $this->getUserId();
        if (!$userId) return [];
        return $this->container->getOrderProfileService()->getProfilesByUserCached($userId);
    }

    function getPersonType()
    {
        $this->getPersonTypes();
        return $this->personTypes[$this->getPersonTypeId()];
    }

    function getUserProfile($profileId = null, $loadDefault = true, $loadLast = true)
    {
        $user = $this->getUser();

        if (!$user) return;

        $personTypeId = $this->getPersonTypeId();
        $profiles = $this->getUserProfiles();
        $personType = $this->getPersonType();

        $profile = $profiles[$profileId];

        if (
            $profile &&
            ($profile['PERSON_TYPE_ID'] == $personTypeId)
        ) {
            return $profile;
        }

        if ($loadDefault) {
            $profile = $this->getUserProfileDefault($loadLast);
        }

        return $profile;
    }

    function getUserProfileIdObtained($profileId = null, $loadDefault = true, $allowCustom = true)
    {
        $profileId = $profileId ?: $this->getProfileId();

        if ($profileId === 'custom') {
            if ($allowCustom)
                return $profileId;
            else
                $profileId = null;
        }

        $profileId = intval($profileId);

        $profile = $this->getUserProfile($profileId, $loadDefault);

        return $profile ? intval($profile['ID']) : null;
    }

    function checkUserBonuses()
    {
        $bonuses = $this->getPropVal('BONUSES');

        if ($bonuses > 0) {
            $newBonuses = $this->checkBonusesValue($bonuses);
            if ($newBonuses != $bonuses) {
                $this->setPropVal('BONUSES', $newBonuses);
                $changed = true;
            }
        }

        return $changed;
    }

    function checkBonusesValue($value, $refetch = false)
    {
        $userId = $this->getUserId();
        $user = $this->getUser();

        if (!$user) {
            $value = 0;
        } else {
            $availableBonuses = $this->container->getSaleClientCardService()->getUserBonuses($userId, $refetch);
            if ($availableBonuses < $value) {
                $value = $availableBonuses;
            }
        }

        return $value;
    }

    function view($check = false)
    {
        if ($check) {
            $this->check(true);
        }
        $this->validate();
    }

    function validate($submit = false)
    {
        $result = $this->getResult();

        $userId = $this->getUserId();
        $order = $this->getOrder();
        $bonuses = $this->getBonuses();

        $paymentType = $this->getPropVal('PAYMENT_TYPE');

        if ($submit) {
            if (!$this->getPropVal('PHONE')) {
                $result->addError(VOrderValidateError::forFieldRequired(null, ['fieldName' => 'phone', 'fieldLabel' => 'Номер телефона']));
            }
            if (!$this->getBasket()->count()) {
                $result->addError(VOrderValidateError::forBasketEmpty());
            }
        }

        $cats = [];
        $productIds = [];
        $productById = [];

        /** @var BasketItem $basketItem */
        foreach ($this->getBasket() as $basketItem) {
            $product = $basketItem->getProduct();
            if ($product) {
                $cats[$product['IBLOCK_SECTION_ID']] = true;
                $productIds[$product['ID']] = $product['ID'];
                $productById[$product['ID']] = $product;
            }
        }

        if ($this->getOrder()->haveDetails()) {
            $time = trim($this->getPropVal('TIME'));
            if ($time) {
                list ($hour, $minute) = explode(':', $time);
                if ($hour && $minute) {
                    $hour = intval($hour);
                    if ($hour >= 0 && $hour < 9) {
                        if (!$this->isSelfPickup()) {

                        } else {
                            if ($this->getPropVal('PICKUP_DEPARTMENT')) {
                                $result->addError(VOrderValidateError::forNightPickupDisable());
                            }
                        }
                    }
                }
            }

            if ($this->isSelfPickup()) {
                $pickupDepartmentId = $this->getPickupDepartmentId();
                if ($pickupDepartmentId) {
                    $pickupDepartment = Office::query()->withViewList()->getById($pickupDepartmentId);
                    if ($pickupDepartment) {
                        if (in_array($pickupDepartmentId, [870800, 870802, 874521, 878043, 878044, 878045])) {
                            $list = [];
                            foreach ($productById as $productId => $product) {
                                if ($productId > 879628) {
                                    // $list[] = $product['NAME'];
                                    break;
                                }
                            }
                            if (!empty($list)) {
                                $result->addError(VOrderValidateError::forBarsProductStopList(null, [
                                    'stopProductNames' => $list,
                                ]));
                            }
                        }
                    }
                }
            }
        }

        if ($bonuses && $userId) {
            $bonusPercent = $this->getBonusesPercent();
            if (!$bonusPercent)
                $bonusPercent = 100;
            if ($bonusPercent < 100) {
                $maxBonuses = ($this->getOrder()->getTotalField('PRICE_BASKET') / 100) * $bonusPercent;
                if ($bonuses > $maxBonuses) {
                    $result->addError(VOrderValidateError::forBonusLagerThenMaxPercent(null, [
                        'maxBonusPercent' => $bonusPercent,
                        'maxBonusValue' => $maxBonuses,
                    ]));
                }
            }
        }

        if ($paymentType === 'online') {
            $onlinePaysystemId = $order->getDepartmentPaysystemId();
            if (!$onlinePaysystemId) {
                $result->addError(VOrderValidateError::forDepartmentOnlinePaymentUnavailable(null, []));
            }
        }

        return $result;
    }

    function getResult(): Result
    {
        if (!isset($this->result)) {
            $this->setResult();
        }
        return $this->result;
    }

    function setResult($result = null)
    {
        $this->result = $result ?? new Result();
    }

    function isDeliveryPriceCalculated()
    {
        return $this->getOrder()->getCustomDeliveryPrice() !== false;
    }

    function getMinOrderPrice()
    {
        return $this->container->getConfigService()->get('SALE.MIN_ORDER_PRICE');
    }

    function getComputedDelivery()
    {
        $this->getComputedDeliveries();
        return $this->computedDeliveries[$this->getDeliveryId()];
    }

    function getComputedDeliveries()
    {
        $result = [];

        $services = Delivery\Services\Manager::getActiveList();

        foreach ($services as $deliveryId => $delivery) {
            if ($deliveryId == 3)
                continue;

            if ($deliveryId == 1)
                $delivery['NAME'] = 'Доставка';

            $item = [
                'ID' => $deliveryId,
                'NAME' => $delivery['NAME'],
                'SERVICE' => $delivery,
                'PRICE' => false
            ];
            $result[$deliveryId] = $item;
        }

        return $result;
    }

    function getDeliveryId()
    {
        $deliveryId = $this->getDeliveryInitId();

        $deliveries = $this->getComputedDeliveries();

        if ($deliveryId && $deliveries[$deliveryId]) {
            $delivery = $deliveries[$deliveryId];
        }

        if (!$delivery) {
            $delivery = current($deliveries);
        }

        if ($delivery) {
            return $delivery['ID'];
        }
    }

    function updateFromClientInput($inputOrder, $action = null)
    {
        if (!empty($inputOrder['attrs'])) {
            $this->updateAttrsFromClientInput($inputOrder['attrs']);
        }
        if (!empty($inputOrder['basket'])) {
            $this->basketSync($inputOrder['basket']);
        }
    }

    function updateAttrsFromClientInput($attrs, $action = null)
    {
        $attrsService = $this->container->getOrderAttributesService();

        $fields = [];
        $props = [];

        foreach ($attrs as $code => $value) {
            $attr = $attrsService->getAttribute($code);
            if ($attr->getAttrKind() === 'field') {
                $fields[$code] = $value;
            } else {
                $props[$code] = $value;
            }
        }

        $order = $this->getOrder();
        $deliveryPriceFieldsHash = $order->getDeliveryPriceFieldsHash();
        $recalculateBasket = false;

        if (!empty($props)) {
            foreach ($props as $propCode => $propValue) {
                switch ($propCode) {
                    case 'PROFILE_ID':
                    case 'PHONE':
                    case 'BONUSES':
                    case 'PROMOCODE':
                    case 'DEPARTMENT_ID':
                    case 'DELIVERY_DEPARTMENT':
                    case 'DEPARTMENT_SERVICE_ID':
                    case 'DELIVERY_PRICE':
                    case 'DELIVERY_FREE_FROM_PRICE':
                    case 'BENEFIT_TYPE':
                    case 'RESERVE_REQUEST_TIME':
                    case 'RESERVE_AVAILABLE_TIME':
                    case 'RESERVE_NEED_TIME':
                    case 'RESERVE_STATUS':
                    case 'RESERVE_SUCCESS_HASH':
                        break;
                    default:
                        $oldValue = $this->getPropVal($propCode);
                        $this->setPropVal($propCode, $propValue);
                }
            }
        } else {
            $props = [];
        }

        if (isset($fields['PERSON_TYPE_ID'])) {
            if (($fields['PERSON_TYPE_ID'] != $this->getPersonTypeId()) || $action === 'person-type-reload') {
                $this->setPersonTypeId($fields['PERSON_TYPE_ID']);
                $this->getProps();
                $this->getComputedPaysystems(true);
            }
        }

        if ($props['PHONE']) {
            $propValue = $props['PHONE'];
            if ($propValue != $this->getPropVal('PHONE')) {
                $this->container->getLoggerService()->addEvent([
                    'name' => 'VORDER_PHONE_CHANGED',
                    'data' => [
                        'VORDER_ID' => $this->getId(),
                        'PHONE' => $propValue
                    ]
                ])->queueAdd();
                $this->setPropVal('PHONE', $propValue);
            }
        }

        if ($this->changeBenefitFromProps($props)) {
            $recalculateBasket = true;
        }

        if ($props['PROFILE_ID']) {
            $propValue = $props['PROFILE_ID'];
            if ($action === 'profile-reload') {
                $this->checkUserProfile($propValue, true);
            } else if ($propValue != $this->getProfileId()) {
                $this->checkUserProfile($propValue);
            }
        }

        if (isset($fields['DELIVERY_ID'])) {
            if ($fields['DELIVERY_ID'] != $this->getDeliveryId()) {
                $this->setDeliveryId($fields['DELIVERY_ID']);
                $recalculateBasket = true;
            }
        }

        if (isset($fields['PAY_SYSTEM_ID'])) {
            if ($fields['PAY_SYSTEM_ID'] != $this->getPaysystemId()) {
                $this->setPaysystemId($fields['PAY_SYSTEM_ID']);
                $order->createOrderPayment($this->getPaysystemId());
            }
        }

        $this->setPropVal('SOURCE', $this->container->getApp()->isMobileApp() ? 'app' : 'site');

        if (isset($fields['USER_DESCRIPTION'])) {
            $this->setOrderField('USER_DESCRIPTION', $fields['USER_DESCRIPTION']);
        }

        $this->refetchProps();

        if ($deliveryPriceFieldsHash !== $order->getDeliveryPriceFieldsHash()) {
            $order->createOrderShipment($this->getDeliveryId());
        }

        if ($recalculateBasket) {
            $this->recalculate();
            $this->reloadBasket();
        }

        return $this;
    }

    function basketSync($clientRows)
    {
        $basket = $this->getBasket();
        $this->container->getBasketService()->mutationSync($clientRows, $basket);
    }

    function changeBenefitFromProps($newProps = [])
    {
        $order = $this->getOrder();

        $oldType = $order->getBenefitType();
        $newType = $newProps['BENEFIT_TYPE'];

        $newBonus = intval($newProps['BONUSES']);
        $oldBonus = $this->getBonuses();

        $newTypeChecked = $this->container->getBenefitService()->checkOrderBenefitType($order, $newType, true);

        $res = false;

        if ($newType !== $newTypeChecked) {
            $res = true;
        }
        if ($newBonus != $oldBonus) {
            $res = true;
        }
        if ($order->isCouponsChanged()) {
            $res = true;
        }

        return $res;
    }

    static function logHandler($name = '', $data)
    {
        file_put_contents(__DIR__ . '/log.txt', $name . json_encode($data, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
    }

    function setPersonTypeId($personTypeId)
    {
        $personTypes = $this->getPersonTypes();
        if (!$personTypeId || !$personTypes[$personTypeId]) {
            $personTypeId = array_keys($personTypes)[0];
        }
        $this->getOrder()->setPersonTypeIdExt($personTypeId);
        $this->setOrderField('PERSON_TYPE_ID', $personTypeId);
    }

    function recalculate()
    {
        //die('ddd');
        $this->basketApplyGifts();
        $this->getOrder()->calculate(true);
        $this->basketSave();
    }

    function basketApplyGifts()
    {
        $basket = $this->getBasket();

        $gifts = $this->calculateOrderGifts();
        $giftFound = false;
        $basketChanged = false;

        foreach ($basket as $i => $item) {

            $productId = $item->getField('PRODUCT_ID');

            if (!$item->getProp('IS_GIFT'))
                continue;

            $gift = $gifts[$productId];

            if (!$gift) {
                $item->delete();
                unset($basket->collection[$i]);
                $basketChanged = true;
            } else {
                $giftFound = true;
                if ($item->getField('QUANTITY') != $gift['QUANTITY']) {
                    $item->setField('QUANTITY', $gift['QUANTITY']);
                    $basketChanged = true;
                }
                unset($gifts[$productId]);
            }
        }

        if (!empty($gifts) && !$giftFound) {

            $gift = array_shift($gifts);

            $currencyCode = CurrencyManager::getBaseCurrency();

            $item = $basket->createItem('catalog', $gift['ELEMENT_ID']);

            $giftElement = \CIBlockElement::GetByID($gift['ELEMENT_ID'])->GetNext();

            $item->setFields(array(
                'QUANTITY' => $gift['QUANTITY'],
                'PRODUCT_ID' => $gift['ELEMENT_ID'],
                'CURRENCY' => $currencyCode,
                'LID' => SITE_ID,
                'NAME' => $giftElement['NAME'],
                'CUSTOM_PRICE' => 'Y',
                'PRICE' => 0,
                'PRODUCT_PROVIDER_CLASS' => class_exists('\Bitrix\Catalog\Product\CatalogProvider') ? '\Bitrix\Catalog\Product\CatalogProvider' : 'CCatalogProductProvider',
                'CATALOG_XML_ID' => $giftElement['IBLOCK_EXTERNAL_ID'],
                'PRODUCT_XML_ID' => $giftElement['EXTERNAL_ID']
            ));

            $propCollection = $item->getPropertyCollection();
            $prop = $propCollection->createItem();

            $prop->setFields(array(
                'NAME' => 'IS_GIFT',
                'CODE' => 'IS_GIFT',
                'VALUE' => 'Y',
                'SORT' => 100
            ));

            $basketChanged = true;
        }

        return $basketChanged;
    }

    function calculateOrderGifts()
    {

        if (!$this->container->isAuthorized()) {
            return [];
        }

        $gifts = [];

        $user = $this->getUser();

        $personalGifts = PersonalGift::query()->filter(['PROPERTY_PHONE' => $user->getPhone(), 'PROPERTY_USED' => 'N'])->withViewList()->getList();

        foreach ($personalGifts as $personalGift) {

            $giftProductId = $personalGift->getProp('GIFT_ELEMENT_ID');

            $gifts[$giftProductId]['ELEMENT_ID'] = $giftProductId;
            $gifts[$giftProductId]['QUANTITY'] = 1;
            $gifts[$giftProductId]['GROUP'] = 'personal';
            $gifts[$giftProductId]['PERSONAL_GIFT_ID'] = $personalGift['ID'];
        }

        foreach ($this->getBasket()->getGiftOffersItems() as $elementId) {
            if (!$gifts[$elementId]) {
                $gifts[$elementId] = [
                    'ELEMENT_ID' => $elementId,
                    'QUANTITY' => 0
                ];
            }
            $gifts[$elementId]['GROUP'] = 'order';
            $gifts[$elementId]['QUANTITY']++;
        }

        $coupons = $this->getCoupons();

        if (!empty($coupons)) {
            foreach ($coupons as $coupon) {
                $elementId = $coupon->getProductId();
                if ($elementId) {
                    if (!$gifts[$elementId]) {
                        $gifts[$elementId] = [
                            'ELEMENT_ID' => $elementId,
                            'QUANTITY' => 0
                        ];
                    }
                    $gifts[$elementId]['GROUP'] = 'order';
                    $gifts[$elementId]['QUANTITY']++;
                }
            }
        }

        return $gifts;
    }

    function ensureOrderEmail()
    {
        $email = $this->getPropVal('EMAIL');

        if (!$email) {
            $user = $this->getUser();
            if ($user)
                $this->setPropVal('EMAIL', $user['EMAIL']);
        }
    }

    function confirm()
    {
        $result = $this->getResult();

        $order = $this->getOrder();
        $isAuthorized = $this->container->isAuthorized();
        $userId = $this->getUserId();

        if (!$userId) {
            $user = $this->ensureUser(true);
            if ($user['ID']) {
                $userId = $user['ID'];
            }
        }

        $user = UserModel::query()->filter(['ID' => $userId])->first();

        $this->orderBeforeConfirm($user);
        $this->checkUserBonuses();
        $bonuses = $this->getBonuses();
        $bonusesPayment = null;

        $paymentType = $this->getPropVal('PAYMENT_TYPE');

        if ($paymentType === 'online') {
            $onlinePaysystemId = $order->getDepartmentPaysystemId();
            if (!$onlinePaysystemId) {
                return false;
            }
            $order->createOrderPayment($onlinePaysystemId);
            foreach ($order->getPaymentCollection() as $payment) {
                if ($payment->isBonus()) {
                    $bonusesPayment = $payment;
                }
            }
        }

        $time = $this->getDeliveryDateTimeUnix() ?: time() + 3600;

        $order->setPropVal('DATETIME', $time);

        //$order->setPropVal('SERVICE_SEND', 'N');

        $order->setFieldNoDemand('USER_ID', $userId);

        $GLOBALS['VORDER_PRESAVE'] = true;

        $order->calcDiscountProps();

        $order->doFinalAction(true);

        $GLOBALS['VORDER_PRESAVE'] = false;

        $order->setFieldNoDemand('PRICE', $this->container->getOrderService()->roundOrderPrice($order->getPrice()));

        foreach ($order->getPaymentCollection() as $payment) {
            $payment->setField('SUM', $this->container->getOrderService()->roundOrderPrice($payment->getField('SUM')));
        }

        $r = $order->save();

        if (!$r->isSuccess()) {
            $result->addError(VOrderValidateError::forSaveFailed(null, [
                'bitrixErrors' => $r->getErrorMessages()
            ]));
            return false;
        }

        if ($bonusesPayment) {
            \CSaleUserAccount::UpdateAccount(
                $userId,
                $bonuses,
                $order->getField("CURRENCY"),
                "Bonus",
                $order->getId()
            );
            $bonusesPayment->setPaid("Y");
        }

        if ($isAuthorized) {
            $this->updateProfileOnOrderSubmit($user);
            $this->updateUserOnOrderSubmit($user);
        }

        $order->save();

        $this['UF_ORDER_ID'] = $this->order->getId();
        $this['UF_USER_ID'] = $userId;

        $this->save();

        $this->container->getLoggerService()->addEvent([
            'type' => 'object',
            'name' => 'ORDER_CREATE',
            'log' => false,
            'data' => [
                'ID' => $order->getId(),
                'FIELDS' => $order->getFields()->getValues(),
                'PROPS' => $order->getPropsValuesByCode()
            ]
        ])->queueAdd();

        if ($order->getPropVal('SERVICE_SEND') === 'Y') {
            $event = new Event('tg', 'order:confirmed', ['order' => $this->order]);
            $event->send();
        }

        return true;
    }

    function ensureUser($create = false)
    {
        $params = [
            'PHONE' => $this->getPropVal('PHONE'),
            'EMAIL' => $this->getPropVal('EMAIL'),
            'NAME' => $this->getPropVal('FIO'),
        ];
        if ($create) {
            if ($user = $this->getUnauthorizedUser($params)) {
                return $user;
            } else {
                return $this->createUnauthorizedUser($params);
            }
        }
    }

    function getUnauthorizedUser($params)
    {
        if ($params['PHONE']) {
            $phone = \Main\Helper\Format::validateMobile($params['PHONE']);
            if ($phone) {
                $foundUser = UserModel::query()->filter([
                    'LOGIC' => 'OR',
                    'LOGIN' => $phone,
                    'PERSONAL_PHONE' => $phone,
                ])->first();
                if ($foundUser) {
                    return $foundUser;
                }
            }
        }
        if ($params['EMAIL']) {
            $email = \Main\Helper\Format::validateEmail($params['EMAIL']);
            if ($email) {
                $foundUser = UserModel::query()->filter([
                    'LOGIC' => 'OR',
                    'LOGIN' => $email,
                    'EMAIL' => $email,
                ])->first();
                if ($foundUser) {
                    return $foundUser;
                }
            }
        }
        return null;
    }

    function createUnauthorizedUser($params)
    {
        $phone = \Main\Helper\Format::validateMobile($params['PHONE']);
        $email = \Main\Helper\Format::validateEmail($params['EMAIL']);

        if (!$email) {
            $autoCreateDomain = $this->container->getUserService()->getAutoCreateEmailDomain();
            $email = $phone . '@' . $autoCreateDomain;
        }

        $user = new \CUser;

        $arFields = array(
            'LOGIN' => $phone,
            'EMAIL' => $email,
            'PERSONAL_PHONE' => $phone,
            'NAME' => $params['NAME'] ?? 'Покупатель',
            'ACTIVE' => "Y",
            'PASSWORD' => $params['PASSWORD'] ?? randString(),
        );

        $userId = $user->Add($arFields);

        if ($userId) {
            return UserModel::instance($userId);
        }
    }

    function orderBeforeConfirm($user)
    {
        $email = \Main\Helper\Format::validateMobile($this->getPropVal('EMAIL'));
        $phone = \Main\Helper\Format::validateMobile($this->getPropVal('PHONE'));

        if ($phone) {
            $this->setPropVal('PHONE', \Main\Helper\Format::phoneView($phone), false);
        }
    }

    function updateProfileOnOrderSubmit($user)
    {
        return;
    }

    function updateUserOnOrderSubmit($user)
    {
        $arUserFields = [];

        $email = \Main\Helper\Format::validateMobile($this->getPropVal('EMAIL'));
        $phone = \Main\Helper\Format::validateMobile($this->getPropVal('PHONE'));
        $fio = $this->getPropVal('FIO');

        if ($fio && !$user['NAME']) {
            $arUserFields['NAME'] = $fio;
        }

        if ($email && !$user->getEmailReal()) {
            if ($user->canChangeEmail($email, true) === true) {
                $arUserFields['EMAIL'] = $email;
            }
        }

        if ($phone && !$user->getPhone()) {
            if ($user->canChangePhone($phone, true) === true) {
                $arUserFields['PERSONAL_PHONE'] = $phone;
            }
        }

        if (!empty($arUserFields)) {
            $user->update($arUserFields);
        }
    }

    function new()
    {
        static::getCurrent($this->getScope(), true);
        return $this;
    }

    function reserve($params)
    {
        $params['forceDeliveryFree'] = $this->isForceDeliveryFree();

        $res = $this->container->getOrderService()->reserve($this->getOrder(), $params, $this->getResult());

        if ($params['timeSave'] && !$res['error']) {
            $this->save();
        }

        return $res;
    }

    function getDepartmentByServiceId($sid)
    {
        return Office::getByServiceId($sid);
    }

    function getBonusesAvailable()
    {
        $userId = $this->container->getUserId();
        return $userId ? $this->container->getSaleClientCardService()->getUserBonuses($userId) : 0;
    }

    function getBonusesPercent()
    {
        $userId = $this->container->getUserId();
        return $userId ? $this->container->getSaleClientCardService()->getUserBonusesPercent($userId) : 0;
    }

    function couponCanAdd()
    {
        $coupons = $this->getCoupons();
        if (count($coupons) > 0) {
            return false;
        } else {
            return true;
        }
    }

    function getClientProfiles()
    {
        $personType = $this->getPersonType();
        $result = [];
        foreach ($this->getUserProfiles() as $profile) {
            if ($profile['PERSON_TYPE_ID'] == $personType['ID']) {
                $result[] = $profile;
            }
        }
        return $result;
    }

    function getClientPersonTypes()
    {
        return $this->getPersonTypes();
    }

    function getDepartments()
    {
        $offices = Office::queryAllCached();
        $result = [];
        foreach ($offices as $id => $office) {
            if ($office['ACTIVE'] !== 'Y')
                continue;
            if ($office['PROPERTY_PAYSYSTEM_ID_VALUE'] || true) {
                $result[$id] = $office;
            }
        }
        return $result;
    }

    function getReserve()
    {
        $list = $this->getReserves([], 1);
        return $list->first();
    }

    function getReserves($filter = [], $limit = 10)
    {
        $filter['UF_VORDER_ID'] = $this['ID'];
        return DeliveryCalculate::query()->filter($filter)->limit($limit)->sort('ID', 'DESC')->getList();
    }

    function getClientAttributes()
    {
        if (!isset($this->clientAttributes)) {

            $userId = $this->container->getUserId();

            $order = $this->getOrder();

            /* @var $props OrderPropertyValue[] */
            $order->getProps();

            $props = $order->propsByCode;

            $this->checkUserProps();

            //$props['RECEIVER_NAME']['VALUE'] = json_encode(['aaa' => 111, 'bbb' => 222]);
            $props['BENEFIT_TYPE']['VALUE'] = $this->container->getBenefitService()->checkOrderBenefitType($order, $props['BENEFIT_TYPE']['VALUE'], true);

            $canUseDiscountBenefit = $this->container->getBenefitService()->canUseOrderBenefitType($order, 'discount');

            $attrs = $this->getAttributes();

            $attrs['PAYMENT_TYPE']->setEnumOptionsFilter(function ($options) use ($userId) {
                if (!$userId) {
                    $options['online']['DISABLE'] = true;
                }
                return $options;
            });

            $attrs['BENEFIT_TYPE']->setEnumOptionsFilter(function ($options) use ($canUseDiscountBenefit) {
                if (!$canUseDiscountBenefit) {
                    $options['discount']['DISABLE'] = true;
                }
                return $options;
            });

            $this->clientAttributes = $attrs;
        }

        return $this->clientAttributes;
    }
}
