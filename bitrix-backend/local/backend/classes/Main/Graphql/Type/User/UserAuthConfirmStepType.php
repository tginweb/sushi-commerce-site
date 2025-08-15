<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserAuthConfirmStepType extends ObjectType
{
    const NAME = 'UserAuthConfirmStep';

    public function getFieldsInfo()
    {
        return [
            'CODE' => Types::string(),
            'NAME' => Types::string(),
            'CAPTION' => Types::string(),
        ];
    }
}
