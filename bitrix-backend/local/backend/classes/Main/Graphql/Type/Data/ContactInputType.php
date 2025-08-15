<?php

namespace Main\Graphql\Type\Data;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class ContactInputType extends InputType
{
    const NAME = 'ContactInput';

    public function getFieldsInfo()
    {
        return [
            'username' => Types::string(),
            'phone' => Types::string(),
            'email' => Types::string(),
            'comment' => Types::string(),
        ];
    }
}
