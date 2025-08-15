<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class PaymentPaysystemStateType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'PaymentPaysystemState',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'READY' => Types::boolean(),
        ];
    }


}
