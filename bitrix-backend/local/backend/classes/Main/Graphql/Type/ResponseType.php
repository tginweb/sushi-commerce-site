<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ResponseType extends ObjectType
{
    const NAME = 'Response';

    public function getFieldsInfo()
    {
        return [
            'success' => Types::boolean(),
            'state' => Types::getNonNull(ResponseStateType::class),
            'payload' => Types::JSON(),
            'error' => Types::getType('ErrorInterface'),
            'errors' => Types::nonNullListOf(Types::nonNull(Types::getType('ErrorInterface'))),
        ];
    }
}
