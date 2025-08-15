<?php

namespace Mail\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Types;

class EventConnectionType extends ObjectType
{
    const NAME = 'MailEventConnection';

    public function getFieldsInfo()
    {
        return [
            'info' => Types::get(QueryInfoType::class),
            'nodes' => Types::listOf(Types::getType('MailEvent')),
        ];
    }
}
