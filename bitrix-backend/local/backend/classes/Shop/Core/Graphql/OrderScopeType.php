<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderScopeType extends ObjectType
{
    const NAME = 'OrderScope';

    public function getFieldsInfo()
    {
        return [
            'CONTRACT_NUM' => Types::string(),
            'ENTITY_TYPE' => Types::string(),
            'ENTITY_ID' => Types::int(),
        ];
    }
}
