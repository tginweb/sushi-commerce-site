<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class AppClientDebugParamsType extends ObjectType
{
    const NAME = 'AppClientDebugParams';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'eventsTransport' => Types::JSON(),
            ];
    }
}
