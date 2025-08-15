<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Sale\Location\GeoIp;
use Main\Service\BaseService;
use Shop\Core\Entity\Location;
use Shop\Core\Entity\LocationCollection;

class LocationService extends BaseService
{
    public $location;
    public $cache = [];

    function getUserStorageLocationCode()
    {
        return $_SESSION['LOCATION_CODE'];
    }

    function setUserStorageLocationCode($code)
    {
        $_SESSION['LOCATION_CODE'] = $code;
        return $this;
    }

    function getUserIp()
    {
        return '37.29.40.229';
        return \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
    }

    function getUserLocationCode()
    {
        if (!$locationCode = $this->getUserStorageLocationCode()) {
            $ipAddress = $this->getUserIp();
            $locationCode = GeoIp::getLocationCode($ipAddress);
        }
        return $locationCode;
    }

    function getUserLocation()
    {
        if (!isset($this->location)) {

            $locationCode = $this->getUserLocationCode();

            if ($locationCode) {
                $this->location = Location::query()->filter(['CODE' => $locationCode])->limit(1)->getList()->first();

                if ($this->location)
                    $this->location->getZip();
            } else {
                $this->location = false;
            }

            $defaultLocations = $this->getDefaultLocations();

            if (!$this->location || !in_array($this->location['ID'], $defaultLocations->getIds())) {
                $this->location = $defaultLocations->first();
            }
        }
        return $this->location;
    }

    /**
     * @return LocationCollection
     */
    public function getDefaultLocations($refetch = false)
    {
        if ($refetch || !isset($this->cache[__METHOD__]))
            $this->cache[__METHOD__] = Location::query()->filterDefault()->getList();
        return $this->cache[__METHOD__];
    }
}



