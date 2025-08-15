<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Types;

class LocationType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Location',
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
            'CODE' => Types::string(),
            'NAME' => Types::string(),
            'REGION' => Types::get(static::class),
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

    public function resolve_NAME($parent) {
        return $parent['NAME_LANG'];
    }

    public function resolve_REGION($parent) {
        return $parent->getRegion();
    }
}
