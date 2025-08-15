<?php

namespace Main\Graphql\Type\Entity;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EntityTypeInfoType extends ObjectType
{
    const NAME = 'EntityTypeInfo';

    public function getFieldsInfo()
    {
        return [
            'name' => Types::string(),
        ];
    }

}
