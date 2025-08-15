<?php

namespace User\Pub\Gql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\User\AppClientType;
use Main\Graphql\Types;

class MutationLogout extends ObjectType
{
    const NAME = 'MutationLogout';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'appClient' => Types::get(AppClientType::class),
                'state' => Types::getNonNull(ResponseStateType::class)
            ];
    }
}
