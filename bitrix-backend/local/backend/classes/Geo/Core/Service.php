<?php

namespace Geo\Core;

use Geo\Core\Lib\GeoCoordinates;
use Location\Distance\Vincenty;
use Main\Service\BaseService;

class Service extends BaseService
{
    public $providers = [];
    public $config = [];

    function getDistance($coord1, $coord2)
    {
        $c1 = GeoCoordinates::create($coord1)->getPhpGeoCoord();
        $c2 = GeoCoordinates::create($coord2)->getPhpGeoCoord();
        $calculator = new Vincenty();
        return $calculator->getDistance($c1, $c2);
    }

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('GEOCODER');
    }

    /**
     * @return \Geo\Core\Provider\GeoCoder\Base
     */
    function getGeoCoderProvider($name = 'default')
    {
        if (isset($this->providers[$name])) {
            $provider = $this->providers[$name];
        } else if ($providerInfo = $this->config['PROVIDERS'][$name]) {
            $provider = $this->providers[$name] = new $providerInfo['class']($providerInfo);
        }

        if (!$provider)
            throw new \Exception('GeoCoder provider not found');

        return $provider;
    }

    function parseAddressPart($part)
    {
        $schema = [
            'область' => ['role' => 'region'],
            'об' => ['role' => 'region', 'replace' => 'область'],
            'обл' => ['role' => 'region', 'replace' => 'область'],

            'городской округ' => ['role' => 'area', 'replace' => 'р-н'],
            'район' => ['role' => 'area', 'replace' => 'р-н'],
            'р-н' => ['role' => 'area', 'replace' => 'р-н'],

            'город' => ['role' => 'city', 'replace' => 'г'],
            'г' => ['role' => 'city', 'replace' => 'г'],
            'деревня' => ['role' => 'city', 'replace' => 'деревня'],
            'рабочий пос(ё|e)лок' => ['role' => 'city', 'replace' => 'р-п'],
            'рп' => ['role' => 'city', 'replace' => 'р-п'],
            'р-п' => ['role' => 'city', 'replace' => 'р-п'],
            'пос(ё|e)лок' => ['role' => 'city', 'replace' => 'п'],
            'село' => ['role' => 'city', 'replace' => 'с'],
            'с' => ['role' => 'city', 'replace' => 'с'],

            'микрорайон' => ['role' => 'district', 'replace' => 'мкр'],
            'мкр' => ['role' => 'district', 'replace' => 'мкр'],
            'квартал' => ['role' => 'district'],
            'жилой комплекс' => ['role' => 'district'],

            'улица' => ['role' => 'street', 'replace' => 'ул'],
            'ул' => ['role' => 'street', 'replace' => 'ул'],
            'бульвар' => ['role' => 'street', 'replace' => 'б-р'],
            'переулок' => ['role' => 'street', 'replace' => 'переулок'],
            'проспект' => ['role' => 'street', 'replace' => 'пр'],
            'пр' => ['role' => 'street', 'replace' => 'пр'],
            'бр' => ['role' => 'street', 'replace' => 'б-р'],
            'б\-р' => ['role' => 'street', 'replace' => 'б-р'],

            'д' => ['role' => 'house', 'replace' => 'д'],
            'кв' => ['role' => 'flat', 'replace' => 'кв'],
        ];

        $res = [];

        foreach ($schema as $pattern => $info) {

            $patterFull = '/(^' . $pattern . '\s+|\s+' . $pattern . '$)/i';

            if (preg_match($patterFull, $part)) {

                $partClean = preg_replace($patterFull, '', $part);

                if ($info['replace']) {
                    if (is_string($info['replace'])) {
                        $partFormatted = $info['replace'] . ' ' . $partClean;
                    } else {
                        $partFormatted = $partClean;
                    }
                } else {
                    $partFormatted = $part;
                }

                $role = $info['role'];

                $res['role'] = $role;
                $res['value'] = $partClean;
                $res['format'] = $partFormatted;
                $res['original'] = $part;

                $res['fields'] = [
                    $role => $partClean,
                    $role . '_format' => $partFormatted,
                    $role . '_original' => $part,
                ];

                break;
            }
        }
        return $res;
    }

    function parseAddress($str, $cleanLocal = [], $default = [])
    {
        $res = $default + [
                'address_full' => null,
                'address' => null,
                'region' => null,
                'area' => null,
                'city' => null,
                'street' => null,
                'street_path' => null,
                'settlement' => null,
                'house' => null,
                'flat' => null,
            ];

        if (!trim($str)) return $res;

        $str = preg_replace('/^(Россия|РФ|Российская федерация)\s*\,/i', '', $str);

        $parts = preg_split('/\s*\,\s*/', trim($str));

        $path = $streetPath = [];
        $prevRole = $role = null;

        foreach ($parts as $index => $part) {

            $part = trim($part);
            $role = null;

            $partRes = $this->parseAddressPart($part);

            if ($partRes['role']) {
                $role = $partRes['role'];
                $res[$role] = $partRes['value'];
                $res[$role . '_format'] = $partRes['format'];
                $res[$role . '_original'] = $partRes['original'];
                $part = $partRes['format'];
            }

            if (!$role) {
                if ($index === 0) {
                    $role = 'city';
                    $res['city'] = $part;
                    $res['city_format'] = 'г ' . $part;
                    $res['city_original'] = $part;
                } else if ($prevRole === 'house') {
                    if (preg_match('/^\d/', $part)) {
                        $res['flat'] = $part;
                        $res['flat_format'] = 'кв ' . $part;
                        $res['flat_original'] = $part;
                    }
                }
            }

            $prevRole = $role;

            if ($cleanLocal[$role] && preg_match('/' . $cleanLocal[$role] . '/i', $part)) {
                continue;
            }

            $path[] = $part;

            if ($role === 'street') {
                $streetPath = $path;
            }
        }

        $res['street_path'] = join(', ', $streetPath);
        $res['address_full'] = join(', ', $path);

        if ($res['flat']) {
            $res['address'] = join(', ', array_slice($path, 0, count($path) - 1));
        } else {
            $res['address'] = join(', ', $path);
        }

        return $res;
    }

    function cleanLocalAddressParts($str)
    {
        $parts = preg_split('/\s*\,\s*/', $str);
        $res = [];
        foreach ($parts as $part) {
            if (preg_match('/(Россия|Иркутская область)/i', $part)) {
                continue;
            }
            $res[] = trim($part);
        }
        return join(', ', $res);
    }
}



