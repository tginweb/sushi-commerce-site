<?php

namespace Menu\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getMenuService()
    {
        return $this->get(Service::class);
    }
}

