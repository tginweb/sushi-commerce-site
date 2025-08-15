<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class VorderInputType extends InputType
{
    const NAME = 'VorderInput';

    public function getFieldsInfo()
    {
        return [
            'attrs' => Types::get(OrderAttributesValueInput::class),
            'basket' => Types::JSON(),
        ];
    }
}
