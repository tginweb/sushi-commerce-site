<?php

namespace Main\Error;

use Main\Graphql\Types;

class OtpError extends BaseError
{
    const TYPE = 'otp';

    function __construct($message, $name, $fields = [])
    {
        parent::__construct($message, $name, $fields);
    }

    public static function getFieldsDefaults()
    {
        return parent::getFieldsDefaults() + [
                'ttl' => 0,
            ];
    }

    public static function getNameEnumValues()
    {
        return ['otp_code_wrong', 'otp_attempts'];
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'param' => Types::int(),

            ] + parent::getGraphQlFieldsInfo();
    }
}
