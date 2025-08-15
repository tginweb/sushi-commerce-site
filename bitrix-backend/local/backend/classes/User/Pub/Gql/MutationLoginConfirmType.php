<?php

namespace User\Pub\Gql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\User\AppClientType;
use Main\Graphql\Types;

class MutationLoginConfirmType extends ObjectType
{
    const NAME = 'MutationLoginConfirm';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'appClient' => Types::get(AppClientType::class),
                'sessionId' => Types::string(),
                'userId' => Types::int(),
                'redirect' => Types::JSON(),
                'status' => $this->statusEnum(['blocked']),
                'state' => Types::getNonNull(ResponseStateType::class)
            ];
    }
}
