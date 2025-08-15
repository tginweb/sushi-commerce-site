<?php

namespace Main\Graphql\Type\Data;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class CoordinatesInputType extends InputType
{
    const NAME = 'CoordinatesInput';

    public function getFieldsInfo()
    {
        return [
            'LON' => Types::float(),
            'LAT' => Types::float(),
        ];
    }
}
