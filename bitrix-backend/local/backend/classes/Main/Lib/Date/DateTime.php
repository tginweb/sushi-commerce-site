<?php

namespace Main\Lib\Date;

class DateTime extends DateBase
{
    function __toString()
    {
        return $this->formatToDateTime();
    }

    static function parse($dateTimeText)
    {
        return static::parseFromDateTime($dateTimeText);
    }
}
