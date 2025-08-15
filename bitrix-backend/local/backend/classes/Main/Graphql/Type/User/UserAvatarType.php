<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class UserAvatarType extends ObjectType
{
    const NAME = 'UserAvatar';

    public function getFieldsInfo()
    {
        return [
            'ELEMENT_ID' => Types::int(),
            'IMAGE' => Types::get(ImageType::class),
        ];
    }
}
