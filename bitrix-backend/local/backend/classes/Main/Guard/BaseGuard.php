<?php

namespace Main\Guard;

use Main\DI\Containerable;

class BaseGuard
{
    use Containerable;

    public $args = [];
    public $context = [];

    function __construct($args = [], $context = [])
    {
        $this->setArgs($args)->setContext($context);
    }

    function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    function setArgs($args)
    {
        $this->args = $args;
        return $this;
    }

    function checkAccess(...$args)
    {
        return $this->access(...$args);
    }

    function access()
    {
        return true;
    }

    function getError()
    {
        return null;
    }
}


