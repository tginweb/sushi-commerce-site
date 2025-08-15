<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderStatusType extends ObjectType
{
    const NAME = 'OrderStatus';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::string(),
            'NAME' => Types::string(),
            'SORT' => Types::int(),
            'COLOR' => Types::string(),
            'TYPE' => Types::string(),
        ];
    }

}
