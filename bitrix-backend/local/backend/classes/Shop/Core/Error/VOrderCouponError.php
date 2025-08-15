<?php

namespace Shop\Core\Error;

use Main\Graphql\Types;

class VOrderCouponError extends VOrderBaseError
{
    const TYPE = 'vorder_coupon';

    public static function getNameEnumValues()
    {
        return [
            'vorder_coupon_apply' => ['message' => 'Неверный или просроченный промокод'],
            'vorder_coupon_delete' => ['message' => 'Ошибка удаления промокода'],
        ];
    }

    static function forApply($message = null, $fields = [])
    {
        return new static('vorder_coupon_apply', $message, $fields);
    }

    static function forDelete($message = null, $fields = [])
    {
        return new static('vorder_coupon_delete', $message, $fields);
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'coupon' => Types::string(),
            ] + parent::getGraphQlFieldsInfo();
    }
}
