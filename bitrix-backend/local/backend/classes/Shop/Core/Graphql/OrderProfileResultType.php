<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;

class OrderProfileResultType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderProfileResult',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'result' => Types::get(ResponseStateType::class),
            'payload' => Types::get(OrderProfileType::class),
            'data' => Types::JSON(),
        ];
    }

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        return $element[$info->fieldName];
    }
}
