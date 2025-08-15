<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Types;

class ElementConnectionType extends ObjectType
{
    const NAME = 'ElementConnection';

    public function getFieldsInfo()
    {
        return [
            'info' => Types::get(QueryInfoType::class),
            'nodes' => Types::listOf(Types::get(ElementType::class)),
        ];
    }
}
