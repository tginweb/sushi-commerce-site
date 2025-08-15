<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderPropType extends ObjectType
{
    const NAME = 'OrderProp';

    public function getFieldsInfo()
    {

        return [
            'ID' => Types::int(),
            'GROUP_ID' => Types::int(),
            'CODE' => Types::string(),
            'SORT' => Types::int(),

            'DEFAULT_VALUE' => Types::string(),
            'PERSON_TYPE_ID' => Types::int(),
            'TYPE' => Types::string(),
            'ROLE' => Types::string(),
            'NAME' => Types::string(),
            'DESC' => Types::string(),

            'IS_REQUIRED' => Types::boolean(),
            'IS_PROFILE' => Types::boolean(),
            'IS_UTIL' => Types::boolean(),
            'IS_PROFILE_NAME' => Types::boolean(),
            'IS_READONLY' => Types::boolean(),

            'PROFILE_TEASER_HIDE' => Types::boolean(),
            'COMPONENT_NAMES' => Types::listOf(Types::string()),
            'OPTIONS' => Types::listOf(Types::get(OrderPropOptionType::class)),
            'PARAMS' => Types::JSON(),
        ];
    }

    public function resolve_GROUP_ID($parent, $args, $context)
    {
        return intval($parent->getGroupId());
    }

    public function resolve_IS_READONLY($parent, $args, $context)
    {
        return $parent->isClientReadonly();
    }

    public function resolve_COMPONENT_NAMES($parent, $args, $context)
    {
        return $parent->getClientComponentNames();
    }

    public function resolve_ROLE($parent, $args, $context)
    {
        return $parent->getRole();
    }

    public function resolve_PARAMS($parent, $args, $context)
    {
        return $parent->getParams();
    }

    public function resolve_PROFILE_TEASER_HIDE($parent, $args, $context)
    {
        return $parent->getParam('PROFILE_TEASER_HIDE');
    }

    public function resolve_IS_PROFILE_NAME($parent, $args, $context)
    {
        return $parent->getField('IS_PROFILE_NAME') === 'Y';
    }

    public function resolve_IS_UTIL($parent, $args, $context)
    {
        return $parent->isUtil();
    }

    public function resolve_IS_REQUIRED($parent, $args, $context)
    {
        return $parent->isRequired();
    }

    public function resolve_IS_PROFILE($parent, $args, $context)
    {
        return $parent->isProfile();
    }

    public function resolve_OPTIONS($parent, $args, $context)
    {
        return $parent->getEnumOptionsCached();
    }
}

