<?php

namespace Main\Lib\Date;

use Bitrix\Main\Type\DateTime;

class DateBase extends DateTime
{
    const FORMAT_DATE = 'd.m.Y';
    const FORMAT_TIME = 'H:i';
    const FORMAT_DATETIME = 'd.m.Y H:i';
    const FORMAT_DATETIME_FULL = 'd.m.Y H:i:s';

    /**
     * @return static
     */
    static function fromTimestamp($timestamp = null)
    {
        if ($timestamp) {
            if ($timestamp instanceof DateTime) {
                return static::fromTimestamp($timestamp->getTimestamp());
            }
            if (is_numeric($timestamp)) {
                if ($timestamp > 2147483647 || $timestamp < -2147483647) {
                    $timestamp = round($timestamp / 1000);
                }
                return parent::createFromTimestamp($timestamp);
            }
        }
    }

    static function parseFromDate($date)
    {
        if ($date) {
            if ($date instanceof DateTime) {
                return static::fromTimestamp($date->getTimestamp());
            } else if (is_numeric($date)) {
                return static::fromTimestamp($date);
            } else if (is_string($date)) {
                return $date ? new static($date, static::FORMAT_DATE) : null;
            }
        }
    }

    static function parseFromDateTime($date)
    {
        if ($date) {
            if ($date instanceof DateTime) {
                return static::fromTimestamp($date->getTimestamp());
            } else if (is_numeric($date)) {
                return static::fromTimestamp($date);
            } else if (is_string($date)) {
                if (preg_match('/\d\d?:\d\d?:\d\d?/', $date)) {
                    return new static($date, static::FORMAT_DATETIME_FULL);
                } else {
                    return new static($date, static::FORMAT_DATETIME);
                }
            }
        }
    }

    static function now()
    {
        return static::createFromTimestamp(time());
    }

    function formatToDate()
    {
        return $this->format(static::FORMAT_DATE);
    }

    function formatToDateTime($withSeconds = false)
    {
        return $withSeconds ? $this->format(static::FORMAT_DATETIME_FULL) : $this->format(static::FORMAT_DATETIME);
    }

    function formatToTime()
    {
        return $this->format(static::FORMAT_TIME);
    }

    function formatToSoap()
    {
        return substr($this->format('c'), 0, -6);
    }

    function formatted($format = null)
    {
        if (!$format) {
            return $this->getTimestamp();
        } else {
            switch ($format) {
                case 'date':
                    $format = static::FORMAT_DATE;
                    break;
                case 'time':
                    $format = static::FORMAT_TIME;
                    break;
                case 'datetime':
                    $format = static::FORMAT_DATETIME;
                    break;
                case 'datetime_full':
                    $format = static::FORMAT_DATETIME_FULL;
                    break;
                case 'timestamp':
                    return $this->getTimestamp();
                    break;
            }
            return $this->format($format);
        }
    }

    static function parse($dateTimeText)
    {
        return static::parseFromDateTime($dateTimeText);
    }

    static function parseAndFormat($source, $format = null)
    {
        $time = static::parse($source);
        return $time ? $time->formatted($format) : null;
    }

    static function parseToTimestamp($source)
    {
        $time = static::parse($source);
        return $time ? $time->getTimestamp() : null;
    }
}
