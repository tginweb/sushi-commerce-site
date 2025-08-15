<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class ElementPropValueHtmlType extends EntityType
{
    const NAME = 'ElementPropValueHtml';

    public function getFieldsInfo()
    {
        return [
            'TEXT' => Types::string(),
            'TYPE' => Types::string(),
        ];
    }
}
