<?php

namespace Company\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class Office extends ElementModel
{
    static function getPropsInfo()
    {
        return [
            'ROLES' => ['view' => true, 'cfilter' => true],
            'ADDRESS' => ['view' => true],
            'PHONES' => ['view' => true],
            'EMAIL' => ['view' => true],
            'COORDINATES' => ['view' => true],
            'REQUISITES' => ['view' => true],
            'WORKTIME' => ['view' => true],
            'SERVICE_ID' => ['view' => true],
            'PHOTOS' => ['view' => true],
        ];
    }

    public static function queryAllCached()
    {
        return static::query()->withViewList()->getList();
    }

    static function getByServiceId($sid)
    {
        return static::query()
            ->filter(['PROPERTY_SERVICE_ID' => $sid])
            ->withViewList()
            ->first();
    }

    public function getUrl()
    {
        return '/office/' . $this['CODE'];
    }

    function getDistance($positionCoords)
    {
        $elementCoords = $this->getCoords();
        if ($elementCoords && $positionCoords) {
            return $this->container->getGeoService()->getDistance($elementCoords, $positionCoords);
        }
    }

    function getCoords()
    {
        $coords = $this->getProp('COORDINATES');
        if (!empty($coords)) {
            list ($lat, $lon) = explode(',', $coords);
            return [
                'LAT' => $lat,
                'LON' => $lon,
            ];
        }
    }
}
