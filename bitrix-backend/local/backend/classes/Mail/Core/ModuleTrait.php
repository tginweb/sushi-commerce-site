<?php

namespace Mail\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getMailService()
    {
        return $this->get(Service::class);
    }
}

