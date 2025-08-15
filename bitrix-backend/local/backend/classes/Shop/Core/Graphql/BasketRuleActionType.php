<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\InterfaceType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Enum\BasketRuleActionTypeEnum;

class BasketRuleActionType extends ObjectType
{
    const NAME = 'BasketRuleAction';

    public function getFieldsInfo()
    {
        return static::getFieldsInfoStatic();
    }

    static function getFieldsInfoStatic()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'TYPE' => Types::getNonNull(BasketRuleActionTypeEnum::class),
        ];
    }

    public static function getGraphQlInterfaceType()
    {
        return new InterfaceType([
            'name' => 'BasketRuleActionInterface',
            'fields' => static::getFieldsInfoStatic(),
            'resolveType' => function ($value) {
                $type = 'BasketRuleAction' . ucfirst(strtolower($value['TYPE']));
                return Types::getType($type, true);
            }
        ]);
    }
}
