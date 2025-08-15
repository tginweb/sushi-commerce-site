<?php

namespace Main\Graphql\Type\Logger;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class LoggerEventResponseType extends ObjectType
{
    const NAME = 'LoggerEventResponse';
    const OBJECT_ACCESS_FIELDS = true;

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'headers' => Types::JSON(),
                'data' => Types::JSON(),
                'errors' => Types::JSON(),
            ];
    }
}
