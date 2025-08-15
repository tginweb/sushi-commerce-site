<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;

class BasketOperationResultType extends ObjectType
{
    const NAME = 'BasketOperationResult';

    public function getFieldsInfo()
    {
        return [
            'state' => Types::get(ResponseStateType::class),
            'payload' => Types::get(BasketType::class)
        ];
    }
}
