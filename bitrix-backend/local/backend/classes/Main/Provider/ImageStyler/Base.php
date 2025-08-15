<?php

namespace Main\Provider\ImageStyler;

use Main\DI\Containerable;

abstract class Base
{
    use Containerable;

    var $conf;

    function __construct($conf)
    {
        $this->conf = $conf;
    }

    abstract function style($imageUrl, $style = null);

    abstract function getTemplate();
}



