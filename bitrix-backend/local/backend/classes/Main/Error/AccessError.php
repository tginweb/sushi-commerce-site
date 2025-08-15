<?php

namespace Main\Error;

use Main\Graphql\Types;

class AccessError extends BaseError
{
    const TYPE = 'access';

    public static function getFieldsDefaults()
    {
        return parent::getFieldsDefaults() + [
                'authRedirect' => false,
            ];
    }

    public static function getNameEnumValues()
    {
        return [
            'access_not_authorized' => ['message' => 'Доступ только для авторизованных пользователей'],
            'access_restricted' => ['message' => 'Доступ запрещен']
        ];
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'authRedirect' => Types::boolean(),
            ] + parent::getGraphQlFieldsInfo();
    }
}
