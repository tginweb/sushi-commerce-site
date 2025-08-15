<?php

namespace Event\Core;

use Event\Core\Service\ClientEmitService;
use Event\Core\Service\ClientNoticeService;
use Event\Core\Service\ClientPushService;
use Main\Service\LoggerService;

trait ModuleTrait
{
    /**
     * @return LoggerService
     */
    function getLoggerService()
    {
        return $this->get(LoggerService::class);
    }

    /**
     * @return ClientEmitService
     */
    function getClientEmitService()
    {
        return $this->get(ClientEmitService::class);
    }

    /**
     * @return ClientNoticeService
     */
    function getClientNoticeService()
    {
        return $this->get(ClientNoticeService::class);
    }

    /**
     * @return ClientPushService
     */
    function getClientPushService()
    {
        return $this->get(ClientPushService::class);
    }
}

