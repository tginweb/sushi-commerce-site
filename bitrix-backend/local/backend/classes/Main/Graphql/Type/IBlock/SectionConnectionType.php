<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Types;

class SectionConnectionType extends ObjectType
{
    const NAME = 'SectionConnection';

    public function getFieldsInfo()
    {
        return [
            'info' => Types::get(QueryInfoType::class),
            'nodes' => Types::listOf(Types::get(SectionType::class)),
        ];
    }
}
