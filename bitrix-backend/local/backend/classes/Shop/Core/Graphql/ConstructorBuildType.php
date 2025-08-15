<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ConstructorBuildType extends ObjectType
{
    const NAME = 'ConstructorBuild';

    public function getFieldsInfo()
    {
        return [
            'SOSTAV' => Types::listOf(Types::get(ConstructorBuildItemType::class)),
            'CONSTRUCTOR_URL' => Types::string(),
            'CONSTRUCTOR_CODE' => Types::string(),
        ];
    }

    public function resolve_CONSTRUCTOR_CODE($element, $args, $ctx)
    {
        return $element->getConstructorCode();
    }

    public function resolve_CONSTRUCTOR_URL($element, $args, $ctx)
    {
        return $element->getConstructorUrl();
    }

    public function resolve_NAME($element, $args, $ctx)
    {
        return $element->getPrimaryTitle();
    }

    public function resolve_SOSTAV($element, $args, $ctx)
    {
        return $element->getSostav();
    }
}
