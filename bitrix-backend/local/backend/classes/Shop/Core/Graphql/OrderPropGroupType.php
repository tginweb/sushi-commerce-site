<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Types;

class OrderPropGroupType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderPropGroup',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'SORT' => Types::int(),
            'PERSON_TYPE_ID' => Types::int(),
        ];
    }

    public function resolveField($parent, $args, $context, ResolveInfo $info)
    {
        $method = 'resolve_' . ucfirst($info->fieldName);
        if (method_exists($this, $method)) {
            return $this->{$method}($parent, $args, $context, $info);
        } else {
            return $parent[$info->fieldName];
        }
    }
}
