<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ElementPropEnumType extends ObjectType
{
    const NAME = 'ElementPropEnum';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'CODE' => Types::string(),
                'NAME' => Types::string(),
                'VALUE' => Types::string(),
                'SORT' => Types::int(),
            ];
    }

    function resolve_CODE($element, $args, $context)
    {
        return $element['XML_ID'];
    }

    function resolve_NAME($element, $args, $context)
    {
        return $element['VALUE'];
    }
}
