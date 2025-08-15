<?php

namespace Menu;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Menu\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Menu\Pub\Module::i()->register($scopes);
        }
    }
}



