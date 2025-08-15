<?php

namespace Main\Lib\Common;

use Main\DI\Containerable;


class Invoker
{
    use Containerable;

    private $className;
    private $methodName;

    public function __construct($className, $methodName = null)
    {
        if (is_array($className)) {
            $this->className = $className[0];
            $this->methodName = $className[1];
        } else {
            $this->className = $className;
            $this->methodName = $methodName;
        }
    }

    public function __invoke(...$args)
    {
        $service = $this->container->get($this->className);
        return $service->{$this->methodName}(...$args);
    }
}
