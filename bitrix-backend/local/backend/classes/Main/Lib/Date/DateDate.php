<?php

namespace Main\Lib\Date;

class DateDate extends DateBase
{
    function __toString()
    {
        return $this->formatToDate();
    }

    static function parse($dateText)
    {
        return static::parseFromDate($dateText);
    }
}
