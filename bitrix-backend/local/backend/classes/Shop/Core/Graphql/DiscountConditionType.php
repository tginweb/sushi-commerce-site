<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class DiscountConditionType extends ObjectType
{
    const NAME = 'DiscountCondition';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'TYPE' => Types::string(),
            'VALUE' => Types::JSON(),
        ];
    }
}
