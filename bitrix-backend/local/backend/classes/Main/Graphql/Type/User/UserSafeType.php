<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserSafeType extends ObjectType
{
    const NAME = 'UserSafe';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'NAME' => Types::string(),
                'NAME_FULL' => Types::string(),
                'AVATAR' => Types::get(UserAvatarType::class),
            ];
    }

    public function resolve_AVATAR($element, $args, $context)
    {
        return $this->container->getUserAvatarService()->getUserAvatar($element);
    }
}
