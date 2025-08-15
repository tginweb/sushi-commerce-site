<?php

namespace Main\DI;

class ContainerBase
{
    use Singletonable;

    public $map = [];
    public $result = [];

    function __construct()
    {

    }

    function callStatic($name, $method, $args = [])
    {
        return call_user_func_array([$this->map[$name], $method], $args);
    }

    function create($name, $value)
    {
        $this->set($name, $value);
        return $this->get($name);
    }

    function set($name, $value)
    {
        $this->map[$name] = $value;
        return $this;
    }

    function get($name)
    {
        if (isset(Singletonable::$instances[$name])) {
            return Singletonable::$instances[$name];
        } else if (isset($this->result[$name])) {
            return $this->result[$name];
        } else {
            $result = false;
            $value = $this->map[$name];

            if (!$value)
                $value = $name;

            if (is_string($value) && class_exists($value)) {
                $result = Singletonable::$instances[$value] = new $value();
            } else if (is_callable($value)) {
                $result = $value();
                $this->result[$name] = $result;
            }

            return $result;
        }
    }

    function getClass($name)
    {
        return $this->map[$name];
    }

    /**
     * @template T
     * @param class-string<T> $name
     * @param mixed $value
     * @return T
     */
    function register($name, $value = null)
    {
        $this->define($name, $value);

        $obj = $this->get($name);

        if (is_object($obj) && method_exists($obj, 'register')) {
            $obj->register();
        }

        return $obj;
    }

    function define($name, $value = null)
    {
        $this->map[$name] = $value;
        return $this;
    }

    function val($name, $args = [])
    {
        if (is_string($this->map[$name]))
            return $this->map[$name];
        else if (is_callable($this->map[$name]))
            return $this->map[$name]();
    }

    function make($name, $args = [])
    {
        if ($this->map) {
            if (is_string($this->map[$name]))
                return new $this->map[$name]();
            else
                $cls = get_class($this->map[$name]);
            return new $cls();
        }
    }

    function __call($name, $args)
    {
        if (preg_match('/__/', $name)) {
            list($service, $method) = explode('__', $name);
            $obj = $this->{$service}();
            return call_user_func_array([$obj, $method], $args);
        }
    }
}

