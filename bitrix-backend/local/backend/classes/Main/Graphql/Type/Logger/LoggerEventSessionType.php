<?php

namespace Main\Graphql\Type\Logger;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class LoggerEventSessionType extends ObjectType
{
    const NAME = 'LoggerEventSession';
    const OBJECT_ACCESS_FIELDS = true;

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ip' => Types::string(),
                'sessionId' => Types::string(),
                'userId' => Types::string(),
                'authorized' => Types::boolean(),
                'fuserId' => Types::int(),
                'phone' => Types::string(),
                'vorderId' => Types::int(),
                'orderId' => Types::int(),
            ];
    }
}
