<?php

namespace Geo\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getGeoService()
    {
        return $this->get(Service::class);
    }
}

