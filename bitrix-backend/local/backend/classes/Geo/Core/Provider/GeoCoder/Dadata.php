<?php

namespace Geo\Core\Provider\GeoCoder;

class Dadata extends Base
{

    function locationsByCoords($lat, $lon) {

        $curl = new \Curl\Curl();

        $apiKey = '26b365bf33da0ed78ba841536dbb413248c21ca0';
        $curl->setHeader('Authorization', 'Token ' . $apiKey);

        $request = [
            'lat' => $lat,
            'lon' => $lon,
            'radius_meters' => 300
        ];

        $res = $curl->get('https://suggestions.dadata.ru/suggestions/api/4_1/rs/geolocate/address', $request);

        foreach ($res->suggestions as $suggestion) {
            $object = (array)$suggestion->data;

            if (!$object['house'])
                continue;

            $object['address'] = $suggestion->value;
            $object['address_full'] = $suggestion->unrestricted_value;
            $object['street_full'] = join(', ', array_filter([
                $object['area_with_type'],
                $object['settlement_with_type'],
                $object['street_with_type'],
            ]));

            $objects[] = $object;
        }

        return $objects;
   }
}
