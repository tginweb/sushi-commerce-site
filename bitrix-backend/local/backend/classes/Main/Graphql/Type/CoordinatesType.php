<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class CoordinatesType extends ObjectType
{
    const NAME = 'Coordinates';

    public function getFieldsInfo()
    {
        return [
            'LON' => Types::nonNull(Types::float()),
            'LAT' => Types::nonNull(Types::float()),
        ];
    }
}
