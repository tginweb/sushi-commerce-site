<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class OrderProfileInput extends InputType
{
    const NAME = 'OrderProfileInput';

    public function getFieldsInfo()
    {
        return [
            'NAME' => Types::string(),
            'PERSON_TYPE_ID' => Types::int(),
            'ATTRS' => Types::get(OrderAttributesValueInput::class),
        ];
    }
}
