<?php

namespace User\Pub\Gql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;

class MutationUserBirthdayType extends ObjectType
{
    const NAME = 'MutationUserBirthday';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'user' => Types::get(UserType::class),
                'state' => Types::get(ResponseStateType::class)
            ];
    }
}
