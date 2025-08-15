<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ResponseRateType extends ObjectType
{
    const NAME = 'ResponseRate';

    public function getFieldsInfo()
    {
        return [
            'ttl' => Types::int(),
            'limited' => Types::boolean(),
        ];
    }
}
