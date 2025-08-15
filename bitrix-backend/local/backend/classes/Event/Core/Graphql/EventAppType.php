<?php

namespace Event\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EventAppType extends ObjectType
{
    const NAME = 'LoggerEventApp';
    const OBJECT_ACCESS_FIELDS = true;

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'env' => Types::string(),
                'side' => Types::string(),
            ];
    }
}
