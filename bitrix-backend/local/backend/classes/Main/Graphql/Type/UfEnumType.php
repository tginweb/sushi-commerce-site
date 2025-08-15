<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class UfEnumType extends ObjectType
{
    const NAME = 'UfEnum';

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

    function resolve_VALUE($element, $args, $context)
    {
        return $element['ID'];
    }
}
