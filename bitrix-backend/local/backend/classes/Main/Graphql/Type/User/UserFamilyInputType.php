<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class UserFamilyInputType extends InputType
{
    const NAME = 'UserFamilyInput';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'BIRTHDAY' => Types::string(),
        ];
    }
}
