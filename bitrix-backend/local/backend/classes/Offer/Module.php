<?php

namespace Offer;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        \Offer\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Offer\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
            //Admin\Module::i()->register($scopes);
        }
    }
}



