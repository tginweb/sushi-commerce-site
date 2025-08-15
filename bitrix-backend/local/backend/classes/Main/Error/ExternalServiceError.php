<?php

namespace Main\Error;

use Main\Graphql\Types;

class ExternalServiceError extends BaseError
{
    const TYPE = 'external_service';

    function __construct($name, $message = null, $fields = [])
    {
        parent::__construct($name, $message, $fields);
    }

    public static function getFieldsDefaults()
    {
        return parent::getFieldsDefaults() + [];
    }

    public static function getNameEnumValues()
    {
        return [
            'es_blocked' => ['message' => 'Превышено число запросов'],
            'es_rate_limit' => ['message' => 'Превышено число запросов'],
            'es_rejected' => ['message' => 'Ошибка внешнего сервиса'],
        ];
    }

    static function _blocked(...$args)
    {
        return new static('es_blocked', ...$args);
    }

    static function _rate_limit(...$args)
    {
        return new static('es_rate_limit', ...$args);
    }

    static function _rejected(...$args)
    {
        return new static('es_rejected', ...$args);
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'authRedirect' => Types::boolean(),
            ] + parent::getGraphQlFieldsInfo();
    }
}
