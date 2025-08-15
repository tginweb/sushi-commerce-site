<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ConditionType extends ObjectType
{
    const ENTITY_TYPE = 'Condition';

    public function getFieldsInfo()
    {
        return [
            'path' => Types::string(),
            'eq' => Types::JSON(),
            'gt' => Types::JSON(),
            'lt' => Types::JSON(),
        ];
    }
}
