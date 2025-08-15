<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Enum\BasketRuleConditionTypeEnum;

class BasketRuleConditionType extends ObjectType
{
    const NAME = 'BasketRuleCondition';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'NOT' => Types::boolean(),
            'TYPE' => Types::nonNull(Types::get(BasketRuleConditionTypeEnum::class)),
            'CODE' => Types::string(),
            'MIN' => Types::int(),
            'MAX' => Types::int(),
            'IN' => Types::listOf(Types::nonNull(Types::JSON())),
            'VALUE' => Types::JSON(),
            'CHILDREN' => Types::nonNullListOf(Types::getNonNull(BasketRuleConditionType::class))
        ];
    }
}


