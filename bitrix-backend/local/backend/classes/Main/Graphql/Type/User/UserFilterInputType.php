<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\Query\QueryFilterInputType;
use Main\Graphql\Types;

class UserFilterInputType extends QueryFilterInputType
{
    const NAME = 'UserFilterInput';

    function getFieldsInfo()
    {
        return [
            'ID' => [
                'type' => Types::IntFilter(),
            ],
            'EMAIL' => [
                'type' => Types::StringFilter(),
            ],
            'LOGIN' => [
                'type' => Types::StringFilter(),
            ],
            'ROLES' => [
                'type' => Types::StringFilter(),
            ],
            'SEARCH' => [
                'type' => Types::string(),
            ],
        ];
    }
}
