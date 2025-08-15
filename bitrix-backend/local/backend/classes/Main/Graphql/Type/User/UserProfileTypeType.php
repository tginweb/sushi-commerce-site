<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserProfileTypeType extends ObjectType
{
    const NAME = 'UserProfileType';

    public function getFieldsInfo()
    {
        return [
            'CODE' => Types::string(),
            'NAME' => Types::string(),
        ];
    }
}
