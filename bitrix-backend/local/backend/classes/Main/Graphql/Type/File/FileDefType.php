<?php

namespace Main\Graphql\Type\File;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class FileDefType extends ObjectType
{
    const NAME = 'FileDef';

    public function getFieldsInfo()
    {
        return [
            'LIMIT_SIZE' => Types::int(),
            'LIMIT_EXT' => Types::string(),
            'LINK_GENERATE' => Types::string(),
        ];
    }
}
