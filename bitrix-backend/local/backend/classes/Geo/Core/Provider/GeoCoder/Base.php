<?php

namespace Geo\Core\Provider\GeoCoder;

use Main\DI\Containerable;

abstract class Base
{
    use Containerable;

    var $conf;

    abstract function locationsByCoords($lat, $lon);
    abstract function locationsByAddress($address);

    function __construct($conf)
    {
        $this->conf = $conf;
    }

    function locationByCoords($lat, $lon)
    {
        $locations = $this->locationsByCoords($lat, $lon);
        return current($locations);
    }

    function locationByAddress($address)
    {
        $locations = $this->locationsByAddress($address);
        return current($locations);
    }
}



