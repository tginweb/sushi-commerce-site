<?php

namespace Event;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Event\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Event\Pub\Module::i()->register($scopes);
        }
    }
}



