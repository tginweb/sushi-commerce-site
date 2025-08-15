<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserGroupType extends ObjectType
{
    const NAME = 'UserGroup';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'TYPE' => Types::string(),
        ];
    }
}
