<?php

namespace Main\Request;

use Main\DI\Containerable;
use Main\DI\Request;
use TG;

class AppSetup
{
    use Containerable;

    public AppScope $appScope;

    function __construct(AppScope $appScope)
    {
        $this->appScope = $appScope;
    }

    function run()
    {
        $container = $this->container;

        \Main\Module::i()->boot();

        $env = $container->getEnvService();

        $appClass = $this->appScope->getAppClass();

        if (!$appClass) {
            return;
        }

        $env->applyAppScope($this->appScope);

        if ($this->appScope->getConfigDir()) {
            $env->loadConfig($this->appScope->getConfigDir());
        }

        $appScope = new $appClass();

        Request::setApp($appScope);

        $appScope->register();
    }
}



