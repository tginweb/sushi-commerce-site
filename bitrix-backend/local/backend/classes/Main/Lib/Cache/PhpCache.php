<?php

namespace Main\Lib\Cache;

class PhpCache
{
    /**
     * @var $this
     */
    private static $instance = null;
    protected $storage = [];

    private function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param $key
     * @param $value
     * @return PhpCache
     */
    public function put($key, $value)
    {
        $this->storage[$key] = $value;

        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->storage[$key]);
    }

    /**
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    /**
     * @param $key
     * @return PhpCache
     */
    public function forget($key)
    {
        unset($this->storage[$key]);

        return $this;
    }

    /**
     * @return PhpCache
     */
    public function flush()
    {
        $this->storage = [];

        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->storage;
    }

    protected function __clone()
    {
    }
}
