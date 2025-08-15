<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class PaysystemComputedType extends ObjectType
{
    const NAME = 'PaysystemComputed';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'SERVICE' => Types::get(PaysystemType::class),
            'CALC_TIMESTAMP' => Types::int(),
            'PRICE' => Types::float(),
            'PRICE_FORMATED' => Types::string(),
        ];
    }

    public function resolve_SERVICE($parent, $args, $ctx)
    {
        return \Bitrix\Sale\PaySystem\Manager::getById($parent['ID']);
    }
}
