<?php

namespace Telegram;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Telegram\Core\Module::i()->register($scopes);
    }
}



