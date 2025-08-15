<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Entity\BasketItem;

class BasketItemPropType extends ObjectType
{
    const NAME = 'BasketItemProp';

    public function getFieldsInfo()
    {
        return [
            'NAME' => Types::string(),
            'CODE' => Types::string(),
            'XML_ID' => Types::string(),
            'VALUE' => Types::JSON(),
        ];
    }

    public function resolve_VALUE($parent, $args, $context, ResolveInfo $info)
    {
        return $this->autotypeValue(BasketItem::getClientPropValue($parent));
    }
}
