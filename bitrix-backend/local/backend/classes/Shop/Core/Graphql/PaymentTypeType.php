<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class PaymentTypeType extends ObjectType
{
    const NAME = 'PaymentType';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'CODE' => Types::string(),
                'NAME' => Types::string(),
                'ICON' => Types::string(),
            ];
    }
}
