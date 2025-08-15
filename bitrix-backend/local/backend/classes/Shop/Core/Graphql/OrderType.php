<?php

namespace Shop\Core\Graphql;

use Company\Core\Graphql\OfficeType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use Shop\Core\Entity\OrderModel;

class OrderType extends ObjectType
{
    const NAME = 'Order';

    public function getFieldsInfo()
    {
        return [
            'TS' => Types::string(),
            'SYNCED' => Types::boolean(),
            'ID' => Types::int(),
            'SERVICE_ID' => Types::int(),

            'PERSON_TYPE_ID' => Types::int(),
            'URL' => Types::string(),
            'USER_ID' => Types::int(),
            'USER' => Types::get(UserType::class),

            'DATE_INSERT' => Types::date(),
            'DATE_UPDATE' => Types::date(),
            'DATE_PAYED' => Types::date(),

            'DATE_FORMATTED' => Types::date(),
            'DATE_TIME_FORMATTED' => Types::date(),

            'PRICE' => Types::float(),
            'PRICE_BASKET' => Types::float(),
            'PRICE_BASKET_BASE' => Types::float(),
            'PRICE_DISCOUNT' => Types::float(),
            'PRICE_DELIVERY' => Types::float(),
            'PRICE_DELIVERY_BASE' => Types::float(),
            'PRICE_TOTAL' => Types::float(),
            'PRICE_TOTAL_BASE' => Types::float(),

            'PRICE_PAY' => Types::float(),
            'PRICE_PAY_BASE' => Types::float(),

            'DISCOUNT_PERCENT' => Types::float(),
            'DISCOUNT_REASON' => Types::string(),

            'DELIVERY_FREE_FROM_PRICE' => Types::int(),

            'DELIVERY_ADDRESS_FULL' => Types::string(),
            'DELIVERY_DATETIME' => Types::date(),

            'DELIVERY_ID' => Types::int(),
            'DELIVERY' => Types::get(DeliveryServiceType::class),
            'DELIVERY_CALCULATED' => Types::boolean(),

            'DELIVERY_DEPARTMENT' => Types::get(OfficeType::class),
            'PICKUP_DEPARTMENT' => Types::get(OfficeType::class),

            'PAYSYSTEM_ID' => Types::int(),
            'PAYSYSTEM' => Types::get(PaysystemType::class),
            'PAYSYSTEM_IS_ONLINE' => Types::boolean(),

            'BASKET' => Types::nonNullListOf(Types::getNonNull(BasketItemType::class)),

            'STATUS' => Types::get(OrderStatusType::class),

            'STATUS_ID' => Types::string(),
            'STATUS_NAME' => Types::string(),
            'STATUS_COLOR' => Types::string(),

            'CSTATUS_ID' => Types::string(),
            'CSTATUS_NAME' => Types::string(),
            'CSTATUS_COLOR' => Types::string(),

            'IS_PAID' => Types::boolean(),
            'IS_ACTIVE' => Types::boolean(),
            'IS_FINISHED' => Types::boolean(),
            'IS_CANCELED' => Types::boolean(),
            'IS_CAN_PAY' => Types::boolean(),
            'IS_CAN_PAY_ONLINE' => Types::boolean(),
            'IS_CAN_PAY_BILL' => Types::boolean(),
            'IS_CAN_CANCEL' => Types::boolean(),

            'USER_DESCRIPTION' => Types::string(),

            'ACCESS_HASH' => Types::string(),
            'SECRET_URL' => Types::string(),

            'ACCOUNT_NUMBER' => Types::string(),

            'PAYMENTS' => Types::listOf(Types::get(PaymentType::class)),

            'BUYER_NAME' => Types::string(),
            'CONTRACT_NUM' => Types::string(),
            'EDU_GROUP_NUM' => Types::string(),
            'STUDENT_FIO' => Types::string(),

            'PAY_LINK' => Types::string(),

            'SCOPE' => Types::get(OrderScopeType::class),
            'SCOPE_ENTITY' => Types::getType('Element'),

            'ACTIONS' => Types::JSON(),
            'COUPONS' => Types::nonNullListOf(Types::getNonNull(CouponType::class)),
            'BONUSES' => Types::int(),


            'COURIER_STATE' => Types::get(CourierStateType::class),
            'ADDRESS_FOR_1C' => Types::string(),

            'CANCEL_REASONS' => Types::listOf(Types::get(OrderCancelReasonType::class)),

            'ATTR' => Types::getNonNull(OrderAttributesValueType::class),
            'ATTRS' => Types::nonNullListOf(Types::getNonNull(OrderAttributeType::class)),
        ];
    }

    public function resolve_DELIVERY_DATE(OrderModel $parent, $args, $ctx)
    {
        return $parent->getCancelReasons();
    }

    public function resolve_CANCEL_REASONS(OrderModel $parent, $args, $ctx)
    {
        return $parent->getCancelReasons();
    }

    public function resolve_ADDRESS_FOR_1C(OrderModel $parent, $args, $ctx)
    {
        return $parent->getAddressFor1c();
    }

    public function resolve_COURIER_STATE(OrderModel $parent, $args, $ctx)
    {
        return null;
        //return $parent->getCourierState();
    }


    public function resolve_BASKET(OrderModel $parent, $args, $ctx)
    {
        return $parent->getBasket()->itemsLoadAll();
    }

    public function resolve_ID(OrderModel $parent, $args, $ctx)
    {
        return $parent->getField('ID');
    }

    public function resolve_DELIVERY_DATETIME(OrderModel $parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getDeliveryDateTimeValue(), $args['format']);
    }

    public function resolve_DELIVERY_ADDRESS_FULL($parent, $args, $ctx)
    {
        return $parent->getAddressFull();
    }

    public function resolve_SCOPE($parent, $args, $ctx)
    {
        $entityType = $parent->getPropValue('CODE', 'SCOPE_ENTITY_TYPE');
        $entityId = $parent->getPropValue('CODE', 'SCOPE_ENTITY_ID');

        return [
            'ENTITY_TYPE' => $entityType,
            'ENTITY_ID' => $entityId,
        ];
    }

    public function resolve_DELIVERY_FREE_FROM_PRICE($parent, $args, $ctx)
    {
        if ($this->container->getDebugService()->isTestRequest()) {
            return 0;
        }

        return $parent->getPropValue('CODE', 'DELIVERY_FREE_FROM_PRICE') ?: 800;
    }

    public function resolve_SCOPE_ENTITY($parent, $args, $ctx)
    {
        $entityType = $parent->getPropValue('CODE', 'SCOPE_ENTITY_TYPE');
        $entityId = $parent->getPropValue('CODE', 'SCOPE_ENTITY_ID');

        return $entityId ? $ctx['dataloader']['element']->load($entityId) : null;
    }

    public function resolve_PAY_LINK($parent, $args, $ctx)
    {
        return $parent->getOnlinePayLink();
    }

    public function resolve_ACTIONS($parent, $args, $ctx)
    {
        return array_values($parent->getAdminActions($this->container->getUser()));
    }

    public function resolve_PAYMENTS($parent, $args, $ctx)
    {
        return $parent->getPaymentCollection();
    }

    public function resolve_SECRET_URL($parent, $args, $ctx)
    {
        return $parent->getSecretUrl();
    }

    public function resolve_ACCESS_HASH($parent, $args, $ctx)
    {
        return $parent->getAccessHash();
    }

    public function resolve_STATUS($parent, $args, $context)
    {
        return (array)$parent->getStatusInfo();
    }

    public function resolve_STATUS_ID($parent, $args, $ctx)
    {
        return $parent->getField('STATUS_ID');
    }

    public function resolve_STATUS_NAME($parent, $args, $ctx)
    {
        return $parent->getStatusName();
    }

    public function resolve_STATUS_COLOR($parent, $args, $ctx)
    {
        return $parent->getStatusColor();
    }

    public function resolve_CSTATUS_ID($parent, $args, $ctx)
    {
        return $parent->getComputedStatusId();
    }

    public function resolve_CSTATUS_NAME($parent, $args, $ctx)
    {
        return $parent->getComputedStatusName();
    }

    public function resolve_CSTATUS_COLOR($parent, $args, $ctx)
    {
        return $parent->getComputedStatusColor();
    }

    public function resolve_IS_PAID($parent, $args, $ctx)
    {
        return $parent->isPaid();
    }

    public function resolve_PAYSYSTEM($parent, $args, $ctx)
    {
        return $parent->getPaysystem();
    }

    public function resolve_PAYSYSTEM_ID($parent, $args, $ctx)
    {
        $paysystem = $parent->getPaysystem();
        return $paysystem ? $paysystem->getField('ID') : $parent->getField('PAY_SYSTEM_ID');
    }

    public function resolve_DELIVERY($parent, $args, $ctx)
    {
        $wrapper = $parent->getDeliveryWrapper();

        if ($wrapper)
            return $wrapper->getMergedFields();
    }

    public function resolve_DELIVERY_ID($parent, $args, $ctx)
    {
        $service = $parent->getDelivery();
        return $service ? $service['ID'] : $parent->getField('DELIVERY_ID');
    }

    public function resolve_DATE_INSERT($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_INSERT'), $args['format']);
    }

    public function resolve_DATE_UPDATE($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_UPDATE'), $args['format']);
    }

    public function resolve_DATE_PAYED($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_UPDATE'), $args['format']);
    }

    public function resolve_DATE_FORMATTED($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_INSERT'), 'date');
    }

    public function resolve_DATE_TIME_FORMATTED($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_INSERT'), 'datetime');
    }

    public function resolve_USER($parent, $args, $context)
    {
        $userId = $parent->getField('USER_ID');

        return $userId > 0 ? $context['dataloader']['user']->load($userId) : null;
    }

    public function resolve_IS_CAN_PAY($parent, $args, $context)
    {
        return $parent->isCanPay();
    }

    public function resolve_IS_CAN_PAY_ONLINE($parent, $args, $context)
    {
        return $parent->isCanPayOnline();
    }

    public function resolve_IS_CAN_PAY_BILL($parent, $args, $context)
    {
        return false;
    }

    public function resolve_IS_FINISHED($parent, $args, $context)
    {
        return $parent->isFinished();
    }

    public function resolve_IS_ACTIVE($parent, $args, $context)
    {
        return $parent->isActive();
    }

    public function resolve_IS_CAN_CANCEL(OrderModel $order, $args, $context)
    {
        return false;
        return $order->isActive() && !$order->isPaid();
    }

    public function resolve_IS_CANCELED($parent, $args, $context)
    {
        return $parent->isCanceled();
    }

    public function resolve_USER_DESCRIPTION($parent, $args, $ctx)
    {
        return $parent->getField('USER_DESCRIPTION');
    }

    public function resolve_DELIVERY_DEPARTMENT($parent, $args, $ctx)
    {
        $id = $parent->getPropValue('CODE', 'DELIVERY_DEPARTMENT');
        return $id ? $ctx['dataloader']['element']->load($id) : null;
    }

    public function resolve_PICKUP_DEPARTMENT($parent, $args, $ctx)
    {
        $id = $parent->getPropValue('CODE', 'PICKUP_DEPARTMENT');
        return $id ? $ctx['dataloader']['element']->load($id) : null;
    }

    public function resolve_COUPONS($parent, $args, $ctx)
    {
        return $parent->getCoupons();
    }

    public function resolve_BONUSES($parent, $args, $ctx)
    {
        return $parent->getBonuses();
    }

    public function resolve_COMPUTED_DELIVERIES($parent, $args, $ctx)
    {
        $parent->getComputedDeliveries();
        $parent->calculateDeliveries();

        return $parent->getComputedDeliveries();
    }

    public function resolve_COMPUTED_PAYSYSTEMS($parent, $args, $ctx)
    {
        return $parent->getComputedPaysystems();
    }

    public function resolve_PRICE($parent, $args, $ctx)
    {
        return $parent->getPrice();
    }

    public function resolve_PRICE_BASKET($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_BASKET');
    }

    public function resolve_PRICE_BASKET_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_BASKET_BASE');
    }

    public function resolve_PRICE_DISCOUNT($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_DISCOUNT');
    }

    public function resolve_PRICE_DELIVERY($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_DELIVERY');
    }


    public function resolve_PRICE_DELIVERY_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_DELIVERY_BASE');
    }


    public function resolve_PRICE_TOTAL($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_TOTAL');
    }

    public function resolve_PRICE_TOTAL_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_TOTAL_BASE');
    }

    public function resolve_PRICE_PAY($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_PAY');
    }

    public function resolve_PRICE_PAY_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getTotalField('PRICE_PAY_BASE');
    }

    public function resolve_PAYSYSTEM_IS_ONLINE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPaysystemIsOnline();
    }

    public function resolve_DISCOUNT_REASON($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPropValue('CODE', 'DISCOUNT_REASON');
    }

    public function resolve_DISCOUNT_PERCENT($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPropValue('CODE', 'DISCOUNT_PERCENT') ?: 0;
    }

    public function resolve_ATTR(OrderModel $parent, $args, $ctx)
    {
        return $parent->getClientAttributes()->getAttributesValues();
    }

    public function resolve_ATTRS(OrderModel $parent, $args, $ctx)
    {
        return $parent->getClientAttributes();
    }
}
