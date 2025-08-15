<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderAttributeOptionType extends ObjectType
{
    const NAME = 'OrderAttributeOption';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'VALUE' => Types::JSON(),
            'NAME' => Types::string(),
            'NAME_SHORT' => Types::string(),
            'SORT' => Types::int(),
            'DESCRIPTION' => Types::string(),
            'XML_ID' => Types::string(),
            'ICON' => Types::string(),
            'DISABLE' => Types::boolean()
        ];
    }

    public function resolve_DISABLE($parent, $args, $ctx)
    {
        return !!$parent['DISABLE'];
    }
}
