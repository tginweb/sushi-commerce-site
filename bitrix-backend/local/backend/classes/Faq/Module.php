<?php

namespace Faq;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{

    function register($scopes = [])
    {
        \Faq\Core\Module::i()->register($scopes);

        if (in_array('public', $scopes)) {
            \Faq\Pub\Module::i()->register($scopes);
        }

        if (in_array('admin', $scopes)) {
            //Admin\Module::i()->register($scopes);
        }
    }
}



