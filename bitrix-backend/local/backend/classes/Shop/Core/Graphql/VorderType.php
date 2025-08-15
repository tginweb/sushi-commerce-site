<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;
use Shop\Core\Entity\VorderCurrent;

class VorderType extends HLEntityType
{
    const NAME = 'Vorder';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'SESSION_ID' => Types::string(),
                'FUSER_ID' => Types::int(),
                'USER_ID' => Types::int(),
                'USER' => Types::get(UserType::class),
                'ORDER_ID' => Types::int(),

                'FIELDS_RAW' => Types::JSON(),
                'PROPS_RAW' => Types::JSON(),

                'PHONE' => Types::string(),
                'EMAIL' => Types::string(),

                'ORDER' => Types::get(OrderType::class),
                'PROFILE_ID' => Types::int(),
                'DELIVERY_DEPARTMENT_ID' => Types::int(),
                'PICKUP_DEPARTMENT_ID' => Types::int(),
                'DEPARTMENT_ID' => Types::int(),
                'BONUSES' => Types::int(),
                'COUPONS' => Types::nonNullListOf(Types::getNonNull(CouponType::class)),

                'BASKET' => [
                    'type' => Types::get(BasketType::class),
                    'args' => [
                        'recalc' => Types::boolean(),
                    ],
                ],

                'ATTR' => Types::getNonNull(OrderAttributesValueType::class),
                'ATTRS' => Types::nonNullListOf(Types::getNonNull(OrderAttributeType::class)),
            ];
    }

    public function resolve_PHONE(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getPhone();
    }

    public function resolve_EMAIL(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getEmail();
    }

    public function resolve_FIELDS_RAW(VorderCurrent $parent, $args, $ctx)
    {
        return $parent['UF_FIELDS'];
    }

    public function resolve_PROPS_RAW(VorderCurrent $parent, $args, $ctx)
    {
        return $parent['UF_PROPS'];
    }

    public function resolve_USER(VorderCurrent $parent, $args, $context)
    {
        $userId = $parent['UF_USER_ID'];
        return $userId > 0 ? $context['dataloader']['user']->load($userId) : null;
    }

    public function resolve_ORDER(VorderCurrent $parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getOrder();
    }

    public function resolve_PROFILE_ID(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getProfileId();
    }

    public function resolve_DELIVERY_DEPARTMENT_ID(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getDeliveryDepartmentId() ?: null;
    }

    public function resolve_PICKUP_DEPARTMENT_ID(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getPickupDepartmentId() ?: null;
    }

    public function resolve_DEPARTMENT_ID(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getDepartmentId() ?: null;
    }

    public function resolve_BASKET(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getBasket();
    }

    public function resolve_BONUSES(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getBonuses();
    }

    public function resolve_COUPONS(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getCoupons();
    }

    public function resolve_ATTR(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientAttributes()->getAttributesValues();
    }

    public function resolve_ATTRS(VorderCurrent $parent, $args, $ctx)
    {
        return $parent->getClientAttributes();
    }
}
