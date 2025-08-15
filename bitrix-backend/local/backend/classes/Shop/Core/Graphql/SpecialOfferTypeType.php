<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SpecialOfferTypeType extends ObjectType
{
    const NAME = 'SpecialOfferType';

    public function getFieldsInfo()
    {
        return [
            'NAME' => Types::string(),
            'CODE' => Types::string(),
            'COLOR' => Types::string(),
        ];
    }

}
