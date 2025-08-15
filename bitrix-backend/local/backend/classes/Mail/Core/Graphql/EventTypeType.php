<?php

namespace Mail\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EventTypeType extends ObjectType
{
    const NAME = 'MailEventType';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'LID' => Types::string(),
            'CODE' => Types::string(),
            'EVENT_NAME' => Types::string(),
            'NAME' => Types::string(),
        ];
    }
}
