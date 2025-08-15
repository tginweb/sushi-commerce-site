<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class CourierStateType extends ObjectType
{
    const NAME = 'CourierState';

    public function getFieldsInfo()
    {
        return [
            'COORDS' => Types::JSON(),
            'ARRIVAL_TIME_CAPTION' => Types::string(),
            'ARRIVAL_TIME' => Types::string(),
            'CAR_COLOR' => Types::string(),
            'CAR_NUMBER' => Types::string(),
        ];
    }
}
