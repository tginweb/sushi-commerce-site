<?php

namespace Main\Graphql\Type\Action;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ActionWebType extends ObjectType
{
    const NAME = 'ActionWeb';

    public function getFieldsInfo()
    {
        return [
            'code' => Types::string(),
            'url' => Types::string(),
            'title' => Types::string(),
        ];
    }
}
