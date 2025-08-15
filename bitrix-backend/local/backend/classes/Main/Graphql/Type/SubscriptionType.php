<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class SubscriptionType extends ObjectType
{
    const NAME = 'Subscription';

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
