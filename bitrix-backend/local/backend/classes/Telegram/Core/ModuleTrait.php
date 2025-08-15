<?php

namespace Telegram\Core;

trait ModuleTrait
{
    /**
     * @return Service
     */
    function getTelegramService()
    {
        return $this->get(Service::class);
    }
}

