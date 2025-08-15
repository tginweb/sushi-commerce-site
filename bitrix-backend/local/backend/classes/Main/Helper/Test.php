<?php

namespace Main\Helper;

class Test
{
    static function assertEqualNumbers($value1, $value2)
    {
        return intval($value1) === intval($value2);
    }

    static function assertEqualNumbersNotEmpty($value1, $value2)
    {
        $num1 = intval($value1);
        $num2 = intval($value2);
        return $num1 && $num2 && ($num1 === $num2);
    }
}
