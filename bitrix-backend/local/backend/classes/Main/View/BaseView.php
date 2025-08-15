<?php

namespace Main\View;

use Main\DI\Containerable;
use Main\DI\Singletonable;

class BaseView
{
    use Singletonable;
    use Containerable;

    function render()
    {
        return '';
    }
}

