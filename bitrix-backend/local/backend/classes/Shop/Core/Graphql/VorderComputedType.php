<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class VorderComputedType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'VorderComputed',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'TS' => Types::int(),


        ];
    }

}
