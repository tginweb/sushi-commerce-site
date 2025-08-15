<?php

namespace Main\Traits;

use Main\Lib\Common\Context;

trait Contextable
{
    var $extContext = null;

    public function getContext()
    {
        if (!isset($this->extContext))
            return $this->extContext = new Context();

        return $this->extContext;
    }

    public function setContext($context)
    {
        $this->extContext = $context;
    }
}
