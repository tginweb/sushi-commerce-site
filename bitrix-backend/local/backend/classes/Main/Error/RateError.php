<?php

namespace Main\Error;

use Main\Graphql\Types;

class RateError extends BaseError
{
    const TYPE = 'rate';

    function __construct($message, $fields = [])
    {
        parent::__construct('rate_limit', $message, $fields);
    }

    public static function getNameEnumValues()
    {
        return [
            'rate_limit' => ['message' => 'Превышено число попыток'],
        ];
    }

    public static function getFieldsDefaults()
    {
        return parent::getFieldsDefaults() + [
                'ttl' => 0,
            ];
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'ttl' => Types::int(),
            ] + parent::getGraphQlFieldsInfo();
    }
}
