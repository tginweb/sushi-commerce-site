<?php

namespace Main\DI;

/**
 * @property Container $container
 */
trait Containerable
{
    function __get($name)
    {
        if ($name === 'container') {
            return Container::instance();
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @return Container
     */
    static function container($name = null)
    {
        return Container::instance($name);
    }

    function getContainer()
    {
        return static::container();
    }

    function getApp()
    {
        return Request::getApp();
    }

    function getHooks()
    {
        return static::container()->getHooks();
    }
}
