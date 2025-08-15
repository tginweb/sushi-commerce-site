<?php

namespace Shop\Core\Entity\Wrapper\Paytype;

use Bitrix;
use Main\DI\Containerable;

class Base
{
    use Containerable;

    public $code;
    public $config = [];

    static $_instances = [];

    /**
     * @return self
     */
    static function wrap($code)
    {
        if (!isset(self::$_instances[$code])) {
            $classSchema = self::container()->getConfigService()->get('SALE.PAYTYPES');
            $type = $classSchema[$code];
            self::$_instances[$code] = new $type['CLASS']($code, $type);
        }

        return self::$_instances[$code];
    }

    function __construct($code, $config = [])
    {
        $this->code = $code;
        $this->config = $config;
    }

    function getPaysystemId()
    {
        return $this->config['PAYSYSTEM_ID'];
    }

    function isOnline()
    {
        return false;
    }

    function isCash()
    {
        return false;
    }

    function isTerminal()
    {
        return false;
    }
}


