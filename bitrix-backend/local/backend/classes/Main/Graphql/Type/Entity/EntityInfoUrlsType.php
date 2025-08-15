<?php

namespace Main\Graphql\Type\Entity;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EntityInfoUrlsType extends ObjectType
{
    const NAME = 'EntityInfoUrls';

    public function getFieldsInfo()
    {
        return [
            'index' => Types::string(),
            'view' => Types::string(),
            'section' => Types::string(),
        ];
    }
}
