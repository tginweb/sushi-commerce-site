<?php

namespace Main\Service;

use TG\Main\Helper;

class ConfigService extends BaseService
{
    public $config = [];
    public $cache = [];

    function register($scopes = [])
    {

    }

    function buildTree($config)
    {
        $result = [];
        foreach ($config as $key => $value) {
            \Main\Helper\Common::arraySetNestedValue($result, $key, $value);
        }
        return $result;
    }

    function getFirst($keys, $def = null)
    {
        foreach ($keys as $key) {
            $value = $this->get($key);
            if ($value) {
                return $value;
            }
        }
        return $def;
    }

    function get($name, $def = null, $cache = true)
    {
        if ($cache && array_key_exists($name, $this->cache))
            return $this->cache[$name];

        $value = null;

        if (isset($this->config[$name])) {
            $value = $this->config[$name];
        } else {
            $keyExists = false;
            $res = \Main\Helper\Common::arrayGetNestedValue($this->config, $name, $keyExists);
            if ($keyExists) {
                $value = $res;
            }
        }

        $this->cache[$name] = !is_null($value) ? $value : $def;

        return $this->cache[$name];
    }

    function getAll()
    {
        return $this->config;
    }

    function mergeConfigFile($file, $vars = [])
    {
        extract($vars);
        $data = include($file);
        if (is_array($data)) {
            $this->mergeConfigData($data);
        }
    }

    function mergeConfigData($data)
    {
        $this->config = array_replace_recursive($this->config, $data);
    }
}


