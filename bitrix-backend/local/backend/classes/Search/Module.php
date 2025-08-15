<?php

namespace Search;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Search\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Search\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
            //Admin\Module::i()->register($scopes);
        }
    }
}



