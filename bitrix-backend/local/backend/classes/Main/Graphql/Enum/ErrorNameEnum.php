<?php

namespace Main\Graphql\Enum;

use Main\Graphql\Types;

class ErrorNameEnum extends BaseEnum
{
    static function options()
    {
        $values = [];
        foreach (Types::$data['error'] as $errorType => $errorClass) {
            foreach ($errorClass::getNameEnumValues() as $key => $info) {
                $value = is_numeric($key) ? $info : $key;
                $values[$value] = $value;
            }
        }
        return $values;
    }
}
