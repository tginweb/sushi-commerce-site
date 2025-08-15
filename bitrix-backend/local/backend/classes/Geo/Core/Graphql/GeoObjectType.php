<?php

namespace Geo\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class GeoObjectType extends ObjectType
{
    const NAME = 'GeoObject';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'geo_lat' => Types::float(),
                'geo_lon' => Types::float(),

                'address_original' => Types::string(),

                'address_full' => Types::string(),
                'address_short' => Types::string(),

                'street_path_full' => Types::string(),
                'street_path_short' => Types::string(),

                'region' => Types::string(),
                'region_format' => Types::string(),
                'region_original' => Types::string(),
                'region_fias_id' => Types::string(),

                'area' => Types::string(),
                'area_format' => Types::string(),
                'area_original' => Types::string(),
                'area_fias_id' => Types::string(),

                'city' => Types::string(),
                'city_format' => Types::string(),
                'city_original' => Types::string(),
                'city_fias_id' => Types::string(),

                'district' => Types::string(),
                'district_format' => Types::string(),
                'district_original' => Types::string(),
                'district_fias_id' => Types::string(),

                'street' => Types::string(),
                'street_format' => Types::string(),
                'street_original' => Types::string(),
                'street_fias_id' => Types::string(),

                'house' => Types::string(),
                'house_format' => Types::string(),
                'house_original' => Types::string(),
                'house_fias_id' => Types::string(),
            ];
    }
}
