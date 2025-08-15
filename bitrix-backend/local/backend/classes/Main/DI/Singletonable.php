<?php

namespace Main\DI;

trait Singletonable
{
    static $instances = [];

    /**
     * @return $this
     */
    static function i()
    {
        if (!isset(static::$instances[static::class])) {
            static::$instances[static::class] = new static();
        }
        return static::$instances[static::class];
    }

    /**
     * @return $this
     */
    static function instance()
    {
        if (!isset(static::$instances[static::class])) {
            static::$instances[static::class] = new static();
        }
        return static::$instances[static::class];
    }
}
