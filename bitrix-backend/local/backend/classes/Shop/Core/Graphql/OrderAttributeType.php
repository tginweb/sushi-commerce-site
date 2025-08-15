<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\EnumType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributeType extends ObjectType
{
    const NAME = 'OrderAttribute';

    public function getFieldsInfo()
    {
        return [
            'KIND' => Types::nonNull(new EnumType([
                'name' => 'OrderAttributeKindEnum',
                'values' => $this->container->getOrderAttributesService()->getKinds(),
            ])),
            'TYPE' => Types::nonNull(new EnumType([
                'name' => 'OrderAttributeTypeEnum',
                'values' => $this->container->getOrderAttributesService()->getTypes()
            ])),
            'CODE' => Types::nonNull(new EnumType([
                'name' => 'OrderAttributeCodeEnum',
                'values' => $this->container->getOrderAttributesService()->getCodes()
            ])),
            'NAME' => Types::string(),
            'DEFAULT_VALUE' => Types::JSON(),
            'VALUE' => Types::JSON(),
            'VALUE_VIEW' => Types::JSON(),
            'OPTIONS' => Types::listOf(Types::get(OrderAttributeOptionType::class)),
        ];
    }

    public function resolve_VALUE_VIEW(IOrderAttribute $parent, $args, $context)
    {
        return $parent->getValueEntity()->getValue('VIEW');
    }

    public function resolve_VALUE(IOrderAttribute $parent, $args, $context)
    {
        return $parent->getValueEntity()->getValue('VALUE');
    }

    public function resolve_DEFAULT_VALUE(IOrderAttribute $parent, $args, $context)
    {
        return $parent->prepareValueMultiplying($parent['DEFAULT_VALUE']);
    }

    public function resolve_KIND(IOrderAttribute $parent, $args, $context)
    {
        return $parent->getAttrKind();
    }

    public function resolve_TYPE(IOrderAttribute $parent, $args, $context)
    {
        return $parent->getAttrType();
    }

    public function resolve_OPTIONS(IOrderAttribute $parent, $args, $context)
    {
        return $parent->getEnumOptionsFilteredList();
    }
}
