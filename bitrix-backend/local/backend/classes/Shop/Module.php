<?php

namespace Shop;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Shop\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Shop\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
           // Admin\Module::i()->register($scopes);
        }
    }
}



