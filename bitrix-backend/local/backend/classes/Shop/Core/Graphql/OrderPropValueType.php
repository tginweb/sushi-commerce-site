<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderPropValueType extends ObjectType
{
    const NAME = 'OrderPropValue';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'CODE' => Types::string(),
            "NAME" => Types::string(),
            'VALUE' => Types::JSON(),
            'VALUE_SHORT' => Types::string(),
            'VALUES' => Types::JSON(),
            'VALUE_VIEW' => Types::string(),
            'VALUE_DESCRIPTION' => Types::string(),
            'IS_READONLY' => Types::boolean(),
            'PROP' => Types::get(OrderPropType::class),
        ];
    }

    public function resolve_ID($parent, $args, $context)
    {
        return $parent->getField('ORDER_PROPS_ID');
    }

    public function resolve_VALUE($parent, $args, $context)
    {
        return $parent->getComputedField('VALUE');
    }

    public function resolve_VALUE_SHORT($parent, $args, $context)
    {
        return $parent->getComputedField('VALUE_SHORT');
    }

    public function resolve_VALUE_VIEW($parent, $args, $context)
    {
        return $parent->getComputedField('VALUE_VIEW');
    }

    public function resolve_VALUE_DESCRIPTION($parent, $args, $context)
    {
        return $parent->getComputedField('VALUE_DESCRIPTION');
    }

    public function resolve_IS_READONLY($parent, $args, $context)
    {
        return $parent->getClientReadonly();
    }

    public function resolve_PROP($parent, $args, $context)
    {
        return $parent->getPropertyModel();
    }
}
