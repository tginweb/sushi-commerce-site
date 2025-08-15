<?php

namespace Geo\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;

/**
 * @property array $city
 */
class AddressModel extends ArrayableModel
{
    use Containerable;

    function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    function __get($name)
    {
        return $this[$name];
    }

    function getPath($parts, $format = 'format', $skipLocal = false)
    {
        $res = [];

        foreach ($parts as $part) {

            $val = null;

            if ($format && !empty($this[$part . '_' . $format])) {
                $val = $this[$part . '_' . $format];
            } else if (!empty($this[$part . '_format'])) {
                $val = $this[$part . '_format'];
            } else if (!empty($this[$part . '_original'])) {
                $val = $this[$part . '_original'];
            } else if (!empty($this[$part])) {
                $val = $this[$part];
            }

            if ($skipLocal) {
                if ($part === 'region' && preg_match('/Иркутская/i', $val)) {
                    continue;
                }
                if ($part === 'city' && preg_match('/Иркутск/i', $val)) {
                    //continue;
                }
            }

            if ($val)
                $res[] = $val;
        }
        return $res;
    }

    function joinPath($parts, $format = 'format', $skipLocal = false)
    {
        $path = $this->getPath($parts, $format, $skipLocal);
        return join(', ', $path);
    }

    function getStreetParts()
    {
        return ['region', 'area', 'city', 'district', 'street'];
    }

    function getStreetPathFull($field = 'format')
    {
        return $this->joinPath($this->getStreetParts(), $field, false);
    }

    function getStreetPathShort($field = 'format')
    {
        return $this->joinPath($this->getStreetParts(), $field, true);
    }

    function getAddressFull($field = 'format')
    {
        return $this->joinPath([...$this->getStreetParts(), 'house'], $field, false);
    }

    function getAddressShort($field = 'format')
    {
        return $this->joinPath([...$this->getStreetParts(), 'house'], $field, true);
    }

    function loadFromYandex($metaData)
    {
        $fields['address_original'] = $metaData->text;

        //die(\Safe\json_encode($metaData->Address->Components));

        foreach ($metaData->Address->Components as $component) {

            $kind = $component->kind;
            $name = $component->name;

            $partRes = $this->getContainer()->getGeoService()->parseAddressPart($name);

            if ($kind === 'province') {
                if ($partRes['role'] === 'region') {
                    $fields += $partRes['fields'];
                }
            } else if ($kind === 'area') {
                if ($partRes['role'] === 'area') {
                    $fields += $partRes['fields'];
                }
            } else if ($kind === 'locality' && !$fields['city']) {
                if ($partRes['role'] === 'city') {
                    $fields += $partRes['fields'];
                } else {
                    $fields['city'] = $name;
                    $fields['city_format'] = 'г ' . $name;
                    $fields['city_original'] = $name;
                }
            } else if ($kind === 'district') {
                if ($partRes['role'] === 'district') {
                    $fields += $partRes['fields'];
                }
            } else if ($kind === 'street') {
                if ($partRes['role'] === 'street') {
                    $fields += $partRes['fields'];
                }
            } else if ($kind === 'house') {
                $fields['house'] = $name;
                $fields['house_format'] = 'д ' . $name;
                $fields['house_original'] = $name;
            } else {
                $fields[$kind] = $name;
            }

            $this->fields += $fields;
        }
    }
}
