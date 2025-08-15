<?php

namespace Main\Graphql\Type\File;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class FileType extends ObjectType
{
    const NAME = 'File';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::id(),
            'SRC' => Types::string(),
            'FILE_SIZE' => Types::int(),
            'ORIGINAL_NAME' => Types::string(),
        ];
    }
}
