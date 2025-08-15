<?php

namespace Shop\Core\Error;

class VOrderSubmitError extends VOrderBaseError
{
    const TYPE = 'vorder_submit';

    public static function getNameEnumValues()
    {
        return [
            'vorder_submit_failed' => ['message' => 'Ошибка создания заказа'],
        ];
    }

    static function forCommon($message = null, $fields = [])
    {
        return new static('vorder_submit_failed', $message, $fields);
    }

    public static function getGraphQlFieldsInfo()
    {
        return [

            ] + parent::getGraphQlFieldsInfo();
    }
}
