<?php

namespace Main\Graphql\Type\Logger;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class LoggerEventRequestType extends ObjectType
{
    const NAME = 'LoggerEventRequest';
    const OBJECT_ACCESS_FIELDS = true;

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'rid' => Types::string(),
                'type' => Types::string(),
                'method' => Types::string(),
                'path' => Types::string(),
                'args' => Types::JSON(),
            ];
    }
}
