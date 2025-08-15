<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class OrderCancelReasonType extends ObjectType
{
    const NAME = 'OrderCancelReason';

    public function getFieldsInfo()
    {
        return [
            'CODE' => Types::string(),
            'NAME' => Types::string(),
        ];
    }
}
