<?php

namespace Geo\Core\Lib;

use Bitrix;
use Location\Coordinate;
use function DI\string;

class GeoCoordinates
{
    public $lat;
    public $lon;
    public $filled;

    function __construct($data, $options = null)
    {
        if (is_array($data)) {
            if (!empty($data[0]) && !empty($data[1])) {
                $this->lat = $data[0];
                $this->lon = $data[1];
            } else if (!empty($data['LAT']) && !empty($data['LON'])) {
                $this->lat = $data['LAT'];
                $this->lon = $data['LON'];
            } else if (!empty($data['lat']) && !empty($data['lon'])) {
                $this->lat = $data['lat'];
                $this->lon = $data['lon'];
            }
        } else if (string($data)) {
            list($this->lat, $this->lon) = preg_split('/[\s\:\;\,\-]+/', $data);
        }

        if (!is_numeric($this->lat) || !is_numeric($this->lon)) {
            $this->filled = false;
        } else {
            if ($this->lat > $this->lon) {
                $lat = $this->lat;
                $this->lat = $this->lon;
                $this->lon = $lat;
            }
            $this->filled = true;
        }
    }

    static function createIfExists($data)
    {
        if (is_object($data)) {
            return $data;
        } else if ($data) {
            return new static($data);
        }
    }

    static function create($data)
    {
        if (is_object($data)) {
            return $data;
        } else {
            return new static($data);
        }
    }

    function getLat()
    {
        return $this->lat;
    }

    function getLon()
    {
        return $this->lon;
    }

    function getLatLon()
    {
        if ($this->isFilled()) {
            return [$this->lat, $this->lon];
        }
    }

    function getLonLat()
    {
        if ($this->isFilled()) {
            return [$this->lon, $this->lat];
        }
    }

    function getData($ucase = true)
    {
        if ($this->isFilled()) {
            return $ucase ? [
                'LAT' => $this->lat,
                'LON' => $this->lon,
            ] : [
                'lat' => $this->lat,
                'lon' => $this->lon,
            ];
        }
    }

    function getPhpGeoCoord()
    {
        return new Coordinate($this->lat, $this->lon);
    }

    function getStringLonLat($delim = ':')
    {
        if ($this->isFilled()) {
            return $this->lon . $delim . $this->lat;
        }
    }

    function getStringLatLon($delim = ':')
    {
        if ($this->isFilled()) {
            return $this->lat . $delim . $this->lon;
        }
    }

    function isFilled()
    {
        return $this->filled;
    }
}



