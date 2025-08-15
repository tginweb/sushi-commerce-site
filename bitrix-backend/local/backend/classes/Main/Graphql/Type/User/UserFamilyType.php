<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;

class UserFamilyType extends HLEntityType
{
    const NAME = 'UserFamily';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'NAME' => Types::string(),
                'RELATION' => Types::string(),
                'USER_ID' => Types::string(),
                'BIRTHDAY' => Types::string(),
            ];
    }

    public function resolve_RELATION($element, $args, $context)
    {
        return 'sss';
    }

}
