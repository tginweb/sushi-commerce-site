<?php

namespace Geo;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Geo\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Geo\Pub\Module::i()->register($scopes);
        }
    }
}



