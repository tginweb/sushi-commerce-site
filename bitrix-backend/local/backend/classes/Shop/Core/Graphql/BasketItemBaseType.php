<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;


class BasketItemBaseType extends ObjectType
{
    use Containerable;

    const NAME = 'BasketItemBase';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::nonNull(Types::int()),
            'UUID' => Types::string(),
            'CLIENT_ID' => Types::nonNull(Types::string()),
            'ORDER_ID' => Types::int(),
            'PRODUCT_ID' => Types::nonNull(Types::int()),
            'NAME' => Types::string(),
            'DESC' => Types::string(),
            'QUANTITY' => Types::nonNull(Types::float()),
            'BASE_PRICE' => Types::nonNull(Types::float()),
            'PRICE_BASE' => Types::nonNull(Types::float()),
            'PRICE' => Types::nonNull(Types::float()),
            'FINAL_PRICE' => Types::nonNull(Types::float()),
            'FINAL_PRICE_BASE' => Types::float(),
            'MEASURE_NAME' => Types::string(),
            'COMMENT' => Types::string(),
            'PAID' => Types::boolean(),
            'INPUT_PROPS_HASH' => Types::string(),
            'DISABLE' => Types::boolean(),
            'DISABLE_REASON' => Types::string(),

            'BENEFIT' => Types::nonNull(new EnumType([
                'name' => static::NAME . 'BenefitType',
                'values' => ['gift', 'special'],
            ])),
            'PARENT_ID' => Types::nonNull(Types::int()),

            'DISCOUNTS' => Types::nonNullListOf(Types::getNonNull(BasketItemDiscountType::class)),

            'CLIENT_CHANGED_AT' => Types::nonNull(Types::int()),
        ];
    }

    public function resolve_INPUT_PROPS_HASH($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getInputPropsHash();
    }

    public function resolve_CLIENT_NAME($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getClientName();
    }

    public function resolve_CLIENT_DESC($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getClientDesc();
    }

    public function resolve_PAID($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getProp('PAID');
    }

    public function resolve_COMMENT($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPropValue('COMMENT');
    }

    public function resolve_BASE_PRICE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getField('BASE_PRICE');
    }

    public function resolve_PRICE_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getField('BASE_PRICE');
    }

    public function resolve_FINAL_PRICE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getFinalPrice();
    }

    public function resolve_FINAL_PRICE_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getFinalPriceBase();
    }

    public function resolve_DISABLE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->isDisable();
    }

    public function resolve_DISABLE_REASON($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getDisableReason();
    }

    public function resolve_CLIENT_ID($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPropValue('CLIENT_ID');
    }

    public function resolve_UUID($parent, $args, $context, ResolveInfo $info)
    {
        return '';
    }
}
