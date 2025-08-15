<?php

namespace Offer\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getOfferService()
    {
        return $this->get(Service::class);
    }
}

