<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ProductMeasureType extends ObjectType
{
    const NAME = 'ProductMeasure';

    public function getFieldsInfo()
    {
        return [
            'NAME' => Types::string(),
            'RATIO' => Types::float(),
        ];
    }
}
