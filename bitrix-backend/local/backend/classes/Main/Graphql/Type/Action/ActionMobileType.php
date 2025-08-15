<?php

namespace Main\Graphql\Type\Action;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ActionMobileType extends ObjectType
{
    const NAME = 'ActionMobile';

    public function getFieldsInfo()
    {
        return [
            'code' => Types::string(),
            'url' => Types::string(),
            'title' => Types::string(),
            'titleAuto' => Types::boolean(),
            'replace' => Types::boolean(),
            'addBacklink' => Types::boolean(),
            'addSession' => Types::boolean(),
            'await' => Types::boolean(),
        ];
    }
}
