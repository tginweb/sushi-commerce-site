<?php

namespace Main\Graphql\Type\Entity;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class EntityInfoType extends ObjectType
{
    const NAME = 'EntityInfo';

    public function getFieldsInfo()
    {
        return [
            'name' => Types::string(),
            'type' => Types::string(),
            'role' => Types::string(),
            'urls' => Types::getNonNull(EntityInfoUrlsType::class),
            'sectionPaths' => Types::string(),
        ];
    }
}
