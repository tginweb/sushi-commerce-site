<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Types;

class OrderConnectionType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderConnection',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'info' => Types::get(QueryInfoType::class),
            'nodes' => Types::listOf(Types::get(OrderType::class)),
        ];
    }
}
