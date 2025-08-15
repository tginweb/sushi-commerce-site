<?php

namespace Push\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;

class PushSubscriberType extends HLEntityType
{
    const NAME = 'PushSubscriber';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'SUBSCRIPTION_ID' => Types::string(),
                'USER_ID' => Types::int(),
                'ENDPOINT' => Types::string(),
                'P256DH_KEY' => Types::string(),
                'AUTH_TOKEN' => Types::string(),
                'ACTIVE' => Types::string(),
                'CREATED_AT' => Types::string(),
                'UPDATED_AT' => Types::string(),
            ];
    }
}
