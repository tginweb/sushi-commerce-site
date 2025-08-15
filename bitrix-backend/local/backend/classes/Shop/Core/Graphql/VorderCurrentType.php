<?php

namespace Shop\Core\Graphql;

use Company\Core\Graphql\OfficeType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;
use Shop\Core\Entity\VorderCurrent;
use Shop\Core\Enum\DeliveryTransportTypeEnum;

class VorderCurrentType extends VorderType
{
    const NAME = 'VorderCurrent';

    public function getFieldsInfo()
    {
        return [
                'TS' => Types::string(),

                'BONUSES_AVAILABLE' => Types::int(),
                'BONUSES_PERCENT' => Types::int(),

                'PERSON_TYPES' => Types::nonNullListOf(Types::getNonNull(PersonTypeType::class)),
                'PAYMENT_TYPES' => Types::nonNullListOf(Types::getNonNull(PaymentTypeType::class)),

                'PROFILES' => Types::nonNullListOf(Types::get(OrderProfileType::class)),
                'DEPARTMENTS' => Types::nonNullListOf(Types::get(OfficeType::class)),
                'DISCOUNTS' => Types::nonNullListOf(Types::getNonNull(DiscountType::class)),

                'DELIVERIES' => Types::nonNullListOf(Types::getNonNull(DeliveryComputedType::class)),
                'TRANSPORT_TYPES' => Types::nonNullListOf(Types::getNonNull(DeliveryTransportTypeEnum::class)),
                'DELIVERY_CALCULATED' => Types::boolean(),
                'DELIVERY_FREE_FROM_PRICE' => Types::float(),

                'COUPON_CAN_ADD' => Types::boolean(),

                'RESPONSE_STATE' => Types::get(ResponseStateType::class),

                'BASKET_RULES' => Types::listOf(Types::get(BasketRuleType::class)),
                'BASKET_RULES_RESULT' => Types::listOf(Types::get(BasketRulesResultType::class)),


                //'RESERVE' => Types::get(VorderReserveType::class),

            ] + parent::getFieldsInfo();
    }

    public function resolve_TS(VorderCurrent $parent, $args, $ctx)
    {
        return date('H:i:s');
    }

    public function resolve_DISCOUNTS(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getDiscounts();
    }

    public function resolve_RESERVE(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getReserve();
    }

    public function resolve_DEPARTMENTS(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getDepartments();
    }

    public function resolve_RESPONSE_STATE(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getResult()->getState();
    }

    public function resolve_PERSON_TYPES(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientPersonTypes();
    }

    public function resolve_PROFILES(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientProfiles();
    }

    public function resolve_COUPON_CAN_ADD(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->couponCanAdd();
    }

    public function resolve_DELIVERY_CALCULATED(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->isDeliveryPriceCalculated();
    }

    public function resolve_DELIVERY_FREE_FROM_PRICE(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getDeliveryFreeFromPrice();
    }

    public function resolve_DELIVERIES(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getComputedDeliveries();
    }

    public function resolve_BONUSES_AVAILABLE(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getBonusesAvailable();
    }

    public function resolve_BONUSES_PERCENT(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getBonusesPercent();
    }

    public function resolve_PAYMENT_TYPES(VorderCurrent $parent, $args, $ctx)
    {
        return $this->container->getPaymentService()->getPaymentTypes();
    }

    public function resolve_ATTR(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientAttributes()->getAttributesValues();
    }

    public function resolve_ATTRS(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientAttributes();
    }

    public function resolve_BASKET_RULES(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getBasketRules();
    }
}
