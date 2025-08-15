<?php

namespace Main\Graphql\Enum;

use Main\Graphql\Types;

class ErrorTypeEnum extends BaseEnum
{
    static function options()
    {
        $values = [];
        foreach (Types::$data['error'] as $errorType => $errorClass) {
            $values[$errorClass::TYPE] = $errorClass::TYPE;
        }
        return $values;
    }
}
