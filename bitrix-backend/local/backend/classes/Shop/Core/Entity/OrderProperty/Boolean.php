<?php

namespace Shop\Core\Entity\OrderProperty;

use Geo\Core\Lib\GeoCoordinates;
use Shop\Core\Entity\OrderProperty;

class Boolean extends OrderProperty
{

    function valueEncode($value)
    {
        return $value ? 'Y' : 'N';
    }

    function valueDecode($value, $forceSingle = false)
    {
        $point = GeoCoordinates::create($value);
        return $point->getData(true);
    }
}


