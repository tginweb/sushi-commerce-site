<?php

namespace Geo\Pub\Api;

use Geo\Core\Graphql\GeoObjectType;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;

class Resolver extends ResolversGenerator
{
    public $ns = 'geo_geocoder_';

    function getQueryMap()
    {
        return [
            'locations_by_coords' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::listOf(Types::get(GeoObjectType::class)),
                    'args' => [
                        'lat' => Types::float(),
                        'lon' => Types::float(),
                    ],
                    'resolve' => [$this, 'queryLocationsByCoords']
                ];
            },
            'location_by_address' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(GeoObjectType::class),
                    'args' => [
                        'address' => Types::string(),
                    ],
                    'resolve' => [$this, 'queryLocationByAddress']
                ];
            },
        ];
    }

    function queryLocationsByCoords($rootValue, $args)
    {
        return $this->container->getGeoService()->getGeoCoderProvider()->locationsByCoords($args['lat'], $args['lon']);
    }

    function queryLocationByAddress($rootValue, $args)
    {
        return $this->container->getGeoService()->getGeoCoderProvider()->locationByAddress($args['address']);
    }
}



