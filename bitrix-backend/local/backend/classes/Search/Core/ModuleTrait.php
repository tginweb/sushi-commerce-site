<?php

namespace Search\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getSearchService()
    {
        return $this->get(Service::class);
    }
}

