<?php

namespace Main\Graphql\Type\Mongo;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Types;

class DocumentConnectionType extends ObjectType
{
    const NAME = 'DocumentConnection';

    public function getFieldsInfo()
    {
        return [
            'info' => Types::get(QueryInfoType::class),
            'nodes' => Types::listOf(Types::get(DocumentType::class)),
        ];
    }
}
