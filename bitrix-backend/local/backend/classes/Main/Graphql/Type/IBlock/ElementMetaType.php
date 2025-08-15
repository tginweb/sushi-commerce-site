<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ElementMetaType extends ObjectType
{
    const NAME = 'ElementMeta';

    public function getFieldsInfo()
    {
        return [
            'TITLE' => Types::string(),
            'KEYWORDS' => Types::string(),
            'DESCRIPTION' => Types::string(),
            'PAGE_TITLE' => Types::string(),
        ];
    }
}
