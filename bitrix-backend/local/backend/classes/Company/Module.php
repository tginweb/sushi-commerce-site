<?php

namespace Company;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Company\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Company\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
            //Admin\Module::i()->register($scopes);
        }
    }
}



