<?php

namespace Main\Graphql\Type\Entity;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EntityType extends ObjectType
{
    const NAME = 'Entity';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
        ];
    }

    public function getPropsInfo()
    {
        return [
        ];
    }
}
