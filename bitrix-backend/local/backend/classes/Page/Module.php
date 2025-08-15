<?php

namespace Page;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Page\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Page\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
            //Admin\Module::i()->register($scopes);
        }
    }
}



