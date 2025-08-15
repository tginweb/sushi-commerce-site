<?php

namespace Main\Service;

use TG\Main\Helper;

class DebugService extends BaseService
{
    public $isDebugRequestResult;
    public $time;

    function isDebugRequest()
    {
        if (!isset($this->isDebugRequestResult)) {
            $this->isDebugRequestResult = $this->container->getConfigService()->get('DEBUG.ENABLE', false);

            $config = self::container()->getConfigService();
            $debugToken = $config->get('MAIN.DEBUG_TOKEN');

            if ($debugToken && ($_SERVER['HTTP_DEBUG_TOKEN'] === $debugToken || $_COOKIE['DEBUG_TOKEN'] === $debugToken)) {
                $this->isDebugRequestResult = true;
                return true;
            }

            $user = $this->container->getUser();
            if ($user && $user->isAdmin()) {
                $this->isDebugRequestResult = true;
                return true;
            }
        }

        return $this->isDebugRequestResult;
    }

    function getTime()
    {
        return $this->time ?? time();
    }

    function setTime($time)
    {
        $this->time = $time;
    }

    function isTestRequest()
    {
        if (!empty($GLOBALS['isMobileApp'])) {
            return false;
        }
        return !$this->container->getApp()->isMobileApp();
        return \Main\Helper\Http::getRealIp() === '94.154.85.69' || \Main\Helper\Http::getRealIp() === '91.149.64.203';
    }
}
