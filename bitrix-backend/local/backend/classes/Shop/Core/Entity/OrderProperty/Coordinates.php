<?php

namespace Shop\Core\Entity\OrderProperty;

use Geo\Core\Lib\GeoCoordinates;
use Main\Graphql\Type\CoordinatesType;
use Main\Graphql\Type\Data\CoordinatesInputType;
use Main\Graphql\Types;
use Shop\Core\Entity\OrderProperty;

class Coordinates extends OrderProperty
{
    function getGraphqlInputType()
    {
        return Types::get(CoordinatesInputType::class);
    }

    function getGraphqlType()
    {
        return Types::get(CoordinatesType::class);
    }

    function valueEncode($value)
    {
        $point = GeoCoordinates::create($value);
        return $point->getStringLatLon();
    }

    function valueDecode($value, $forceSingle = false)
    {
        $point = GeoCoordinates::create($value);
        return $point->getData(true);
    }

    public function prepareValueFields($value, $order = null, $validate = false)
    {
        if ($value) {
            $point = GeoCoordinates::create($value);
            if ($point->isFilled()) {
                $value = $point->getData();
                $valueView = $point->getStringLatLon();
            } else {
                $value = null;
                $valueView = null;
            }
            return [
                'VALUE' => $value,
                'VIEW' => $valueView
            ];
        }
    }

    function haveView()
    {
        return true;
    }
}


