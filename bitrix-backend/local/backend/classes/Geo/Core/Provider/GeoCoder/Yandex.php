<?php

namespace Geo\Core\Provider\GeoCoder;

use Geo\Core\Entity\AddressModel;
use TG\Main\Helper;

class Yandex extends Base
{
    function getApiKey()
    {
        $keys = $this->conf['apiKeys'];
        return $keys[array_rand($keys)];
    }

    function locationsByAddress($address)
    {
        $curl = new \Curl\Curl();


        $objects = [];

        $request = [
            'apikey' => $this->getApiKey(),
            'geocode' => $address,
            'results' => 1,
            //kind: 'house',
            'format' => 'json',
            'sco' => 'latlong'
        ];


        $res = $curl->get('https://geocode-maps.yandex.ru/1.x/', $request);

        if ($res) {

            if ($res->response->GeoObjectCollection->featureMember[0]) {

                $object = [];

                $geoObject = $res->response->GeoObjectCollection->featureMember[0]->GeoObject;

                $geoObjectComponents = $geoObject->metaDataProperty->GeocoderMetaData->Address->Components;

                foreach ($geoObjectComponents as $component) {
                    if (!$object[$component->kind])
                        $object[$component->kind] = $component->name;
                }

                $object['address'] = $geoObject->metaDataProperty->GeocoderMetaData->text;

                $location = $object['address'];
                $location = preg_replace('/,\s*д\.?[^\,]+/', '', $location);
                $location = preg_replace('/Россия,?/', '', $location);
                $location = preg_replace('/Иркутская область,/', '', $location);
                $location = preg_replace('/улица,/', ',', $location);

                $object['location'] = trim($location);

                $coords = $geoObject->Point->pos;

                list($lon, $lat) = explode(' ', $coords);

                $object['geo_lon'] = $lon;
                $object['geo_lat'] = $lat;

                $objects[] = $object;
            }
        }

        return $objects;
    }

    function locationsByCoords($lat, $lon)
    {
        $geoService = $this->container->getGeoService();

        $locations = [];

        $curl = new \Curl\Curl();
        $geocode = $lat . ',' . $lon;

        $request = [
            'apikey' => $this->getApiKey(),
            'geocode' => $geocode,
            'results' => 1,
            'kind' => 'house',
            'format' => 'json',
            'sco' => 'latlong',
            //'spn' => '0.8,0.8',
            //'rspn' => 1
        ];

        for ($i = 1; $i <= 2; $i++) {
            $res = $curl->get('https://irkutsk.sushi-studio.ru/api-new/geocoder.php', $request);
            if ($res && is_string($res) && json_decode($res))
                $res = json_decode($res);
            //file_put_contents(__DIR__.'/blocked.txt', json_encode($res)."\n", FILE_APPEND);
            if ($res) {
                if ($res->statusCode === 403) {
                    file_put_contents(__DIR__ . '/blocked.txt', json_encode([$res, $request['apikey']]) . "\n", FILE_APPEND);
                } else {
                    break;
                }
            }
            $request['apikey'] = $this->getApiKey();
        }

        if (!$res)
            return $locations;


        $geocoderMetaData = \Main\Helper\Common::getNestedValue($res, 'response.GeoObjectCollection.featureMember.0.GeoObject.metaDataProperty.GeocoderMetaData');


        if ($geocoderMetaData) {

            //$objectCoordsStr = Helper\Common::getNestedValue($res, 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos');
            //list ($objectCoordsLon, $objectCoordsLat) = explode(' ', $objectCoordsStr);
            //$dis = $this->container->getGeoService()->getDistance($lat.':'.$lon, $objectCoordsLat.':'.$objectCoordsLon);

            //file_put_contents(__DIR__.'/zzAAA.txt', json_encode($dis)."\n", FILE_APPEND);

            $address = new AddressModel();

            $address->loadFromYandex($geocoderMetaData);

            /*
            die(\Safe\json_encode([
                'fields' => $address->fields,
                'address_full' => $address->getAddressFull(),
                'address_short' => $address->getAddressShort(),
                'street_path_full' => $address->getStreetPathFull(),
                'street_path_short' => $address->getStreetPathShort(),
                '$geocoderMetaData' => $geocoderMetaData
            ]));
            */

            /*
            $data = $geoService->parseAddress(
                $object['address'],
                [
                    'area' => 'Иркутская'
                ],
                [
                    'address_original' => $object['address'],
                    'house' => $object['house'],
                    'house_format' => $object['house']
                ]
            );
            */

            $data = $address->fields;

            $data['address_full'] = $address->getAddressFull();
            $data['address_short'] = $address->getAddressShort();

            $data['street_path_full'] = $address->getStreetPathFull();
            $data['street_path_short'] = $address->getStreetPathShort();

            $data['geo_lat'] = $lat;
            $data['geo_lon'] = $lon;

            $locations[] = $data;
        }

        return $locations;
    }
}
