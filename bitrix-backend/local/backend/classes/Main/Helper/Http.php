<?php

namespace Main\Helper;

use Bitrix\Main\Application;

class Http
{
    static function makeAbsoluteUrl($url)
    {

        $server = self::requestServer();

        if ($url[0] === '/') {
            $url = $server . '/' . ltrim($url, '/');
        }

        return $url;
    }

    static function requestServer()
    {
        $protocol = static::requestIsSecure() ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'];
    }

    static function requestIsSecure()
    {
        if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }

        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    static function getRealIp()
    {
        $ip = false;

        $xList = [];

        $xForwarded = Application::getInstance()->getContext()->getServer()->get('HTTP_X_FORWARDED_FOR');
        $xRealIp = Application::getInstance()->getContext()->getServer()->get('HTTP_X_REAL_IP');

        if ($xForwarded)
            $xList[] = $xForwarded;

        if ($xRealIp)
            $xList[] = $xRealIp;

        $xList = join(', ', $xList);

        if (!empty($xList)) {
            $ips = explode(", ", $xList);
            $fCount = count($ips);

            for ($i = 0; $i < $fCount; $i++) {
                if (!preg_match("/^(10|172\\.16|192\\.168)\\./", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        if (!$ip) {
            $ip = trim(Application::getInstance()->getContext()->getRequest()->getRemoteAddress());
        }

        return $ip;
    }

    static function getRequestHttpHeader(...$names)
    {
        foreach ($names as $name) {
            if (isset($_SERVER['HTTP_' . $name]))
                return $_SERVER['HTTP_' . $name];
            else if (isset($_SERVER['HTTP_X_' . $name]))
                return $_SERVER['HTTP_X_' . $name];
        }
    }
}


