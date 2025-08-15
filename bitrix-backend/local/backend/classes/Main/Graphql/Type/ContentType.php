<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ContentType extends ObjectType
{
    const NAME = 'Content';

    public function getFieldsInfo()
    {
        return [
            'TEXT' => Types::string(),
            'TYPE' => Types::string(),
        ];
    }
}
