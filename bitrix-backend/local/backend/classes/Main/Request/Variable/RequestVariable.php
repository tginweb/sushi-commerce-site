<?php

namespace Main\Request\Variable;

use Main\DI\Containerable;
use Main\Error\CommonError;

class RequestVariable
{
    use Containerable;

    public $name;
    public $isRequired = false;

    function __construct($name)
    {
        $this->name = $name;
    }

    static function create($name)
    {
        return new static($name);
    }

    function required()
    {
        $this->isRequired = true;
        return $this;
    }

    function getValue($arguments)
    {
        return $arguments[$this->name] ?? null;
    }

    function throwInvalid()
    {
        $this->throwError(null, "Argument '{$this->name}' is invalid");
    }

    function throwAccessError()
    {
        $this->throwError(null, "Argument '{$this->name}' value restriction");
    }

    function throwError($errorName, $errorMessage)
    {
        throw new CommonError($errorName, $errorMessage);
    }

    function extract($arguments)
    {
        $value = $this->getValue($arguments);
        if ($this->isRequired) {

            $isEmpty = false;
            if (isset($value)) {
                if (is_scalar($value)) {
                    if ($value === '' || $value === null || $value === 0) {
                        $isEmpty = true;
                    }
                }
            } else {
                $isEmpty = true;
            }

            if ($isEmpty) {
                $this->throwError(null, "Argument '{$this->name}' is required");
            }
        }
        $value = $this->extractValue($value, $arguments);
        return $value;
    }

    function extractValue($value, $arguments)
    {
        return $value;
    }
}



