<?php

namespace Mail;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Mail\Core\Module::i()->register($scopes);
    }
}



