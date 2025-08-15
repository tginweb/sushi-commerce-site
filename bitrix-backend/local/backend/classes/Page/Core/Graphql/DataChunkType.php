<?php

namespace Page\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class DataChunkType extends ObjectType
{
    const NAME = 'PageDataChunk';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'CODE' => Types::string(),
                'TYPE' => Types::string(),
                'VALUE' => Types::JSON(),
            ];
    }
}
