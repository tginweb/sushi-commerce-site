<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserSessionType extends ObjectType
{
    const NAME = 'UserSession';

    public function getFieldsInfo()
    {
        return [
            'SESSION_ID' => Types::string(),
            'USER_ID' => Types::int(),
            'FUSER_ID' => Types::int(),
        ];
    }
}
