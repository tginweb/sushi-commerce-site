<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class CommandType extends ObjectType
{
    const NAME = 'Command';

    public function getFieldsInfo()
    {
        return [
            'code' => Types::string(),
            'type' => Types::string(),
            'path' => Types::string(),
            'params' => Types::JSON(),
            'confirm' => Types::boolean(),
        ];
    }
}
