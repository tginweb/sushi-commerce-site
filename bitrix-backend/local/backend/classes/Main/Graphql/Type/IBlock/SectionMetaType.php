<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SectionMetaType extends ObjectType
{
    const NAME = 'SectionMeta';

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
