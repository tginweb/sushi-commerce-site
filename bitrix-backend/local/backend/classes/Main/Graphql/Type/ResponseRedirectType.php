<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ResponseRedirectType extends ObjectType
{
    const NAME = 'ResponseRedirect';

    public function getFieldsInfo()
    {
        return [
            'ttl' => Types::int(),
            'restricted' => Types::boolean(),
        ];
    }
}
