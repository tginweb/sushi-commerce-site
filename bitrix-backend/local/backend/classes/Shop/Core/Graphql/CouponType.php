<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;

class CouponType extends ElementType
{
    const NAME = 'Coupon';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::nonNull(Types::int()),
            'NAME' => Types::string(),
            'COUPON' => Types::string(),
            'PRODUCT_ID' => Types::int(),
            //'MODE' => Types::int(),
            //'STATUS' => Types::int(),
            //'STATUS_CODE' => Types::string(),
            //'STATUS_CODE_TEXT' => Types::string(),
            'PRODUCT' => Types::getType('ProductElement'),
        ];
    }

    public function resolve_PRODUCT_ID($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getProp('PRODUCT_ID');
    }

    public function resolve_COUPON($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getProp('COUPON');
    }

    public function resolve_PRODUCT($parent, $args, $context, ResolveInfo $info)
    {
        if ($parent->getProp('PRODUCT_ID'))
            return $context['dataloader']['element']->load($parent->getProp('PRODUCT_ID') . '.list');
    }
}
