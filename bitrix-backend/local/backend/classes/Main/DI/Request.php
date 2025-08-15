<?php

namespace Main\DI;

use Main\Lib\Common\BaseApp;

class Request
{
    static $app;

    /**
     * @return BaseApp
     */
    static function getApp() {
        return static::$app;
    }

    static function setApp($app) {
        static::$app = $app;
    }
}

