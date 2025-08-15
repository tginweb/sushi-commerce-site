<?php

namespace Main\Helper;

use Main\DI\Containerable;
use Mihanentalpo\FioAnalyzer\FioAnalyzer;
use const TG\Main\Helper\SITE_DIR;
use const TG\Main\Helper\SITE_ID;
use const TG\Main\Helper\SITE_SERVER_NAME;

class Str
{
    use Containerable;

    /*
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public static function makeLink($text, $url, $query = [], $options = [])
    {
        $url = self::makeUrl($url, $query, $options);

        return '<a href="' . $url . '">' . $text . '</a>';
    }

    public static function makeUrl($url, $query = [], $options = [])
    {
        if (is_array($url)) {
            $data = $url;
        } else {
            $data = [];
        }

        $data += $options + [
                'url' => $url,
                'query' => $query,
                'fragment' => null,
                'absolute' => false,
                'production' => false,
            ];

        $url = $data['url'];

        if (!empty($data['query'])) {
            $url .= '?' . http_build_query($data['query']);
        }

        if (!empty($data['fragment'])) {
            $url .= '#' . $data['fragment'];
        }

        if ($data['absolute']) {
            if ($data['production']) {
                $siteUrl = self::container()->getConfigService()->get('APP.PROD_URL');
            } else {
                $siteUrl = Http::requestServer();
            }

            $url = $siteUrl . '/' . ltrim($url, '/');
        }

        return $url;
    }

    public static function ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    /**
     * Меняет значение параметра в URL.
     * Если параметра нет, то он добавляется в конец URL.
     * Если значение параметра равно NULL, то он вырезается из URL.
     * Так же дописывает или удаляет идентификатор сессии.
     *
     * @param string $url если не задан, то берется из $_SERVER['REQUEST_URI']
     * @param string/array  $arg         может быть так же ассоциативным массивом,
     *                                      в этом случае третий параметр не используется
     * @param string/null   $value
     * @param bool $is_use_sid дописывать/вырезать параметр с идентификатором сессии?
     *                                      идентификатор сессии дописывается для только для текущего хоста
     * @return   string
     * @see      http_build_query()
     *
     * @author   Nasibullin Rinat <n a s i b u l l i n  at starlink ru>
     * @charset  ANSI
     * @version  1.2.0
     */
    public static function urlReplaceArg($url, $arg, $value = null, $is_use_sid = false)
    {
        static $tr_table = array(
            '\[' => '(?:\[|%5B)',
            '\]' => '(?:\]|%5D)',
            '%5B' => '(?:\[|%5B)',
            '%5D' => '(?:\]|%5D)',
        );

        if (is_string($arg)) $args = array($arg => $value);
        elseif (is_array($arg)) $args = $arg;
        else {
            trigger_error('An array or string type expected in second parameter, ' . gettype($arg) . ' given ', E_USER_WARNING);
            return $url;
        }
        if (!$url) $url = $_SERVER['REQUEST_URI'];
        $original_url = $url;

        /*
        при необходимости дописываем/вырезаем параметр с идентификатором сессси,
        т.к. output_add_rewrite_var() тупо добавит еще один
        May be good idea to make output_REPLACE_rewrite_var() function? :)
        http://bugs.php.net/bug.php?id=43234
        */
        $args[session_name()] = null;
        if ($is_use_sid && session_id()) {
            $host = parse_url($url, PHP_URL_HOST);
            if (!$host || $host === $_SERVER['HTTP_HOST']) $args[session_name()] = session_id();
        }

        $count = 0;

//echo "<b>$url</b><br>";

        $url = str_replace('?', '?&', $url, $count);
        if ($count > 1) {
            trigger_error('Incorrect URL with more then one "?"!', E_USER_WARNING);
            return $original_url;
        }

        foreach ($args as $arg => $value) {
            #проверяем название параметра на допустимые символы
            if (!preg_match('/^[^\?&#=\x00-\x20\x7f]+$/s', $arg)) {
                trigger_error('Illegal characters found in arguments. See second parameter (' . gettype($arg) . ' type given)!', E_USER_WARNING);
                return $original_url;
            }
            $re_arg = strtr(preg_quote($arg, '/'), $tr_table);
            if (preg_match('/(&' . $re_arg . '=)[^\?&#=\x00-\x20\x7f]*/s', $url, $m)) {
                #заменяем или вырезаем параметр, если он существует
                $v = is_null($value) ? '' : $m[1] . rawurlencode($value);
                $url = str_replace($m[0], $v, $url);
                continue;
            }
            if (is_null($value)) continue; #вырезаем параметр из URL
            #добавляем параметр в конец URL
            $div = strpos($url, '?') !== false ? '&' : '?';
            $url = $url . $div . $arg . '=' . rawurlencode($value);
        }#foreach
        return rtrim(str_replace('?&', '?', $url), '?');
    }

    public static function compile($template, $arParams = array())
    {
        $arPatterns = array("#SITE_DIR#", "#SITE#", "#SERVER_NAME#");
        $arReplace = array(SITE_DIR, SITE_ID, SITE_SERVER_NAME);

        foreach ($arParams as $key => $value) {
            $arPatterns[] = "{" . $key . "}";
            $arReplace[] = $value;

            $arPatterns[] = "#" . $key . "#";
            $arReplace[] = $value;
        }
        return str_replace($arPatterns, $arReplace, $template);
    }

    public static function fioParse($str)
    {
        $fa = new FioAnalyzer();
        $parts = $fa->break_apart($str);

        $result = [];

        foreach ($parts as $field => $fieldInfo) {
            $result[$field] = $fieldInfo['src'];
        }

        return $result;
    }

    public static function transliterate($textcyr = null, $textlat = null)
    {
        $cyr = array(
            'ж', 'ч', 'щ', 'ш', 'ю', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж', 'Ч', 'Щ', 'Ш', 'Ю', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
        $lat = array(
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
        if ($textcyr) return str_replace($cyr, $lat, $textcyr);
        else if ($textlat) return str_replace($lat, $cyr, $textlat);
        else return null;
    }

    static function camelCase($string, $capitalizeFirstCharacter = true, $separator = '_-')
    {
        $string = strtolower($string);
        $str = str_replace(str_split($separator), '', ucwords($string, $separator));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
