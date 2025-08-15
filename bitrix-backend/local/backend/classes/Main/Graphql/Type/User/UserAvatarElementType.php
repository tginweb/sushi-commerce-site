<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;

class UserAvatarElementType extends ElementType
{
    const NAME = 'UserAvatarElement';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'CODE' => Types::string(),
        ];
    }
}
