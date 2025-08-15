<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class BasketRuleConditionTypeEnum extends BaseEnum
{
    public static function options()
    {
        return [
            'group_or' => 'или',
            'group_and' => 'и',
            'order_price' => 'сумма заказа',
            'product_id' => 'товар',
            'section_id' => 'секция товара',
            'bonus_filled' => 'используются бонусы',
            'coupons_filled' => 'используются купоны',
            'delivery_id' => 'служба доставки',
            'transport_type' => 'способ доставки',
            'paysystem_id' => 'платежная система',
            'payment_type' => 'способ оплаты',
            'attr_value' => 'значение аттрибута'
        ];
    }
}
