<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;

class OrderResultType extends ObjectType
{
    const NAME = 'OrderResult';

    public function getFieldsInfo()
    {
        return [
            'result' => Types::get(ResponseStateType::class),
            'payload' => Types::get(OrderType::class),
        ];
    }

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        return $element[$info->fieldName];
    }
}
