<?php

namespace Page\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ContentChunkType extends ObjectType
{
    const NAME = 'PageContentChunk';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'GROUP' => Types::string(),
                'NAME' => Types::string(),
                'CODE' => Types::string(),
                'VALUE' => Types::string(),
                'TYPE' => Types::string(),
            ];
    }
}
