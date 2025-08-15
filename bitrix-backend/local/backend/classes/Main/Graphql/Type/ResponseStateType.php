<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ResponseStateType extends ObjectType
{
    const NAME = 'ResponseState';

    public function getFieldsInfo()
    {
        return [
            'messages' => Types::nonNullListOf(Types::getNonNull(MessageType::class)),
            'events' => Types::JSON(),
            'rate' => Types::get(ResponseRateType::class),
            'redirect' => Types::get(ResponseRedirectType::class),
        ];
    }
}
