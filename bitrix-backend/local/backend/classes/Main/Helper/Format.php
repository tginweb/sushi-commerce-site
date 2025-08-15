<?php

namespace Main\Helper;

class Format
{

    static function validateMobile($phone)
    {

        $phone = trim($phone);

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 10 && !in_array($phone[0], array('7', '8'))) {
            $phone = '7' . $phone;
        }

        if (strlen($phone) == 11 && $phone[0] == '8') {
            $phone[0] = '7';
        }

        if (strlen($phone) != 11) {
            return false;
        }

        return $phone;
    }

    static function validateEmail($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    static function phoneView($sPhone)
    {
        $sPhone = preg_replace("/[^0-9]/", '', $sPhone);
        $sPhone = preg_replace("/^7/", '', $sPhone);
        $sArea = substr($sPhone, 0, 3);
        $sPrefix = substr($sPhone, 3, 3);
        $sNumber = substr($sPhone, 6, 4);
        $sPhone = "+7 (" . $sArea . ") " . $sPrefix . "-" . $sNumber;
        return $sPhone;
    }

    static function number2string($number)
    {

        // обозначаем словарь в виде статической переменной функции, чтобы
        // при повторном использовании функции его не определять заново
        static $dic = array(

            // словарь необходимых чисел
            array(
                -2 => 'две',
                -1 => 'одна',
                1 => 'один',
                2 => 'два',
                3 => 'три',
                4 => 'четыре',
                5 => 'пять',
                6 => 'шесть',
                7 => 'семь',
                8 => 'восемь',
                9 => 'девять',
                10 => 'десять',
                11 => 'одиннадцать',
                12 => 'двенадцать',
                13 => 'тринадцать',
                14 => 'четырнадцать',
                15 => 'пятнадцать',
                16 => 'шестнадцать',
                17 => 'семнадцать',
                18 => 'восемнадцать',
                19 => 'девятнадцать',
                20 => 'двадцать',
                30 => 'тридцать',
                40 => 'сорок',
                50 => 'пятьдесят',
                60 => 'шестьдесят',
                70 => 'семьдесят',
                80 => 'восемьдесят',
                90 => 'девяносто',
                100 => 'сто',
                200 => 'двести',
                300 => 'триста',
                400 => 'четыреста',
                500 => 'пятьсот',
                600 => 'шестьсот',
                700 => 'семьсот',
                800 => 'восемьсот',
                900 => 'девятьсот'
            ),

            // словарь порядков со склонениями для плюрализации
            array(
                array('рубль', 'рубля', 'рублей'),
                array('тысяча', 'тысячи', 'тысяч'),
                array('миллион', 'миллиона', 'миллионов'),
                array('миллиард', 'миллиарда', 'миллиардов'),
                array('триллион', 'триллиона', 'триллионов'),
                array('квадриллион', 'квадриллиона', 'квадриллионов'),
                // квинтиллион, секстиллион и т.д.
            ),

            // карта плюрализации
            array(
                2, 0, 1, 1, 1, 2
            )
        );

        // обозначаем переменную в которую будем писать сгенерированный текст
        $string = array();

        // дополняем число нулями слева до количества цифр кратного трем,
        // например 1234, преобразуется в 001234
        $number = str_pad($number, ceil(strlen($number) / 3) * 3, 0, STR_PAD_LEFT);

        // разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
        // т.к. мы не знаем максимальный порядок числа и будем бежать снизу
        // единицы, тысячи, миллионы и т.д.
        $parts = array_reverse(str_split($number, 3));

        // бежим по каждой части
        foreach ($parts as $i => $part) {

            // если часть не равна нулю, нам надо преобразовать ее в текст
            if ($part > 0) {

                // обозначаем переменную в которую будем писать составные числа для текущей части
                $digits = array();

                // если число треххзначное, запоминаем количество сотен
                if ($part > 99) {
                    $digits[] = floor($part / 100) * 100;
                }

                // если последние 2 цифры не равны нулю, продолжаем искать составные числа
                // (данный блок прокомментирую при необходимости)
                if ($mod1 = $part % 100) {
                    $mod2 = $part % 10;
                    $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag * $mod1;
                    } else {
                        $digits[] = floor($mod1 / 10) * 10;
                        $digits[] = $flag * $mod2;
                    }
                }

                // берем последнее составное число, для плюрализации
                $last = abs(end($digits));

                // преобразуем все составные числа в слова
                foreach ($digits as $j => $digit) {
                    $digits[$j] = $dic[0][$digit];
                }

                // добавляем обозначение порядка или валюту
                $digits[] = $dic[1][$i][(($last %= 100) > 4 && $last < 20) ? 2 : $dic[2][min($last % 10, 5)]];

                // объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
                array_unshift($string, join(' ', $digits));
            }
        }

        // преобразуем переменную в текст и возвращаем из функции, ура!
        return join(' ', $string);
    }

    static function toOptions($items, $optionValue = 'value', $optionLabel = 'name', $byValue = false)
    {
        $result = [];

        foreach ($items as $key => $item) {
            $option = [];

            if (is_array($item)) {
                $option = $item;
                if (!isset($item[$optionValue])) {
                    $option[$optionValue] = $key;
                }
                if (!isset($item[$optionLabel])) {
                    $option[$optionLabel] = $key;
                }
            } else if (is_numeric($key)) {
                $option[$optionValue] = $item;
                $option[$optionLabel] = $item;
            } else {
                $option[$optionValue] = $key;
                $option[$optionLabel] = $item;
            }

            if ($byValue) {
                $result[$option[$optionValue]] = $option;
            } else {
                $result[] = $option;
            }
        }

        return $result;
    }

    static function getJsValueDeep($value)
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => &$item) {
                $result[$key] = self::getJsValue($item);
            }
            $value = $result;
        } else {
            self::getJsValue($value);
        }
        return $value;
    }

    static function getJsValue($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        // Быстрая проверка на целое число
        if (ctype_digit($value) || ($value[0] === '-' && ctype_digit(substr($value, 1)))) {
            return (int)$value;
        }

        // Проверка на float
        if (is_numeric($value)) {
            $fval = (float)$value;
            // Если число целое (42.0 → 42)
            return $fval == (int)$fval ? (int)$fval : $fval;
        }

        return $value;
    }
}
