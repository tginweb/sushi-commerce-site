<?php

namespace User\Pub\Gql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;

class MutationUserEmailType extends ObjectType
{
    const NAME = 'MutationUserEmail';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'user' => Types::get(UserType::class),
                'state' => Types::get(ResponseStateType::class)
            ];
    }
}
