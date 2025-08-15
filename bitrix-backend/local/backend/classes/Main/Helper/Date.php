<?php

namespace Main\Helper;

use Bitrix\Main\Type\DateTime;
use function TG\Main\Helper\MakeTimeStamp;

class Date
{
    const DURATION_MINUTE = 60;
    const DURATION_HOUR = 3600;
    const DURATION_DAY = 3600 * 24;

    static function secToStr($secs)
    {
        $num_word = function ($value, $words, $show = true) {
            $num = $value % 100;
            if ($num > 19) {
                $num = $num % 10;
            }

            $out = ($show) ? $value . ' ' : '';
            switch ($num) {
                case 1:
                    $out .= $words[0];
                    break;
                case 2:
                case 3:
                case 4:
                    $out .= $words[1];
                    break;
                default:
                    $out .= $words[2];
                    break;
            }

            return $out;
        };

        $res = '';

        $days = floor($secs / 86400);
        $secs = $secs % 86400;
        if ($days)
            $res .= $num_word($days, array('день', 'дня', 'дней')) . ', ';

        $hours = floor($secs / 3600);
        $secs = $secs % 3600;
        if ($hours)
            $res .= $num_word($hours, array('час', 'часа', 'часов')) . ', ';

        $minutes = floor($secs / 60);
        $secs = $secs % 60;
        if ($minutes)
            $res .= $num_word($minutes, array('минута', 'минуты', 'минут')) . ', ';

        if ($secs)
            $res .= $num_word($secs, array('секунда', 'секунды', 'секунд'));

        return trim($res, ' ,');
    }

    static function formatDate($date, $args = [])
    {
        $args += [
            'withDate' => true,
            'withTime' => false,
            'fromFormat' => false,
            'format' => true,
            'formatDefault' => false,
            'addTimezone' => false
        ];

        $format = 'U';

        if ($args['formatDefault'] || $args['format'] === 'default' || $args['format'] === true) {

            if ($args['withDate'])
                $format = 'd.m.Y';

            if ($args['withTime'])
                $format .= ' H:i';

        } else if ($args['format']) {
            $format = $args['format'];
        }

        if (!$date) {
            return;
        }

        if (is_numeric($date) && intval($date)) {
            $ts = $date;
        } else {
            if (is_object($date)) {
                $date = $date->toString();
            }
            $ts = MakeTimeStamp($date, $args['fromFormat']);
        }

        if ($args['addTimezone']) {
            $ts += 5 * 3600;
        }

        $res = mb_strtolower(FormatDate($format, $ts));

        if ($format === 'U') {
            return intval($res);
        }

        return $res;
    }

    static function ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

}
