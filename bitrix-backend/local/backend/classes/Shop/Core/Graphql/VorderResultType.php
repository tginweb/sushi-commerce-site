<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;

class VorderResultType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'VorderResult',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'state' => Types::get(ResponseStateType::class),
            'payload' => Types::get(VorderType::class),
            'data' => Types::JSON(),
        ];
    }

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        return $element[$info->fieldName];
    }
}
