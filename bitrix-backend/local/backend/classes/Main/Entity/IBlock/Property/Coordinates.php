<?php

namespace Main\Entity\IBlock\Property;

use Geo\Core\Lib\GeoCoordinates;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\IBlock\PropertyModel;
use Main\Graphql\Type\CoordinatesType;
use Main\Graphql\Type\Data\CoordinatesInputType;
use Main\Graphql\Types;

class Coordinates extends PropertyModel
{
    function getGglInputClass()
    {
        return Types::get(CoordinatesInputType::class);
    }

    function getGglTypeClass()
    {
        return Types::get(CoordinatesType::class);
    }

    function gqlResolve(ElementModel $parent, $args, $context, ResolveInfo $info)
    {
        $val = $parent->getProp($info->fieldName);
        return $this->prepareValue($val);
    }

    function prepareValue($value)
    {
        if (!empty($value)) {
            $point = GeoCoordinates::create($value);
            if ($point->isFilled()) {
                return $point->getData();
            }
        }
    }

    function gqlResolveInput($value)
    {
        if ($value) {
            $point = GeoCoordinates::create($value);
            if ($point->isFilled()) {
                return $point->getStringLatLon(',');
            }
        }
    }
}



