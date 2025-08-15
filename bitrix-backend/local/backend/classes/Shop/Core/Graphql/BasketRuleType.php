<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\EnumType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketRuleType extends ObjectType
{
    const NAME = 'BasketRule';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'PARENT' => Types::string(),
            'CODE' => Types::nonNull(Types::string()),
            'NAME' => Types::string(),
            'NAME_TEMPLATE' => Types::string(),
            'CAPTION' => Types::string(),
            'PERCENT' => Types::int(),
            'HOTEST' => Types::boolean(),
            'LEVEL_STOP' => Types::boolean(),
            'ALL_STOP' => Types::boolean(),
            'CONDITIONS' => Types::nonNullListOf(Types::getNonNull(BasketRuleConditionType::class)),
            'ACTIONS' => Types::nonNullListOf(Types::nonNull(Types::getType('BasketRuleActionInterface'))),
            'CHILDREN' => Types::nonNullListOf((Types::getNonNull(BasketRuleType::class))),
            'TYPE' => Types::nonNull(new EnumType([
                'name' => static::NAME . 'Type',
                'values' => ['common', 'discount', 'gift'],
            ])),
        ];
    }
}


$rules = [
    [
        'CODE' => 'benefit',
        'TYPE' => 'group',
        'CHILDREN_LOGIC' => 'OR',
        'CHILDREN' => [
            [
                'CODE' => 'bonus',
                'NAME' => 'Bonus',
                'CONDITIONS' => [
                    [
                        'TYPE' => 'bonus.filled',
                    ]
                ],
                'ACTIONS' => [
                    [
                        'TYPE' => 'allow:bonuses',
                    ],
                    [
                        'TYPE' => 'coupon.delete',
                    ],
                ]
            ],
            [
                'CODE' => 'discount',
                'NAME' => 'Discount',
                'CONDITIONS' => [
                    [
                        'TYPE' => 'order.deliveryType',
                        'VALUE' => 'pickup'
                    ]
                ],
                'ACTIONS' => [
                    [
                        'TYPE' => 'discount',
                        'VALUE' => 15
                    ]
                ]
            ],
            [
                'CODE' => 'coupon',
                'NAME' => 'Promocode',
                'CONDITIONS' => [
                    [
                        'TYPE' => 'coupon.filled',
                    ]
                ],
                'ACTIONS' => [
                    [
                        'TYPE' => 'allow.coupon',
                    ]
                ]
            ]
        ]
    ]
];
