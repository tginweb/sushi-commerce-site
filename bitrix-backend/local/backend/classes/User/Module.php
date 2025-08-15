<?php

namespace User;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \User\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \User\Pub\Module::i()->register($scopes);
        }
    }
}



