<?php

namespace Main\Service;

use Bitrix\Main\Loader;
use TG\Main\Helper;

class SettingsService extends BaseService
{
    public $settings;

    function register($scopes = [])
    {
    }

    function get($name, $def = null)
    {
        $this->load();

        if (array_key_exists($name, $this->settings))
            return $this->settings[$name];

        $value = null;

        if (isset($this->settings[$name])) {
            $value = $this->settings[$name];
        } else {
            $keyExists = false;
            $res = \Main\Helper\Common::arrayGetNestedValue($this->settings, $name, $keyExists);
            if ($keyExists) {
                $value = $res;
            }
        }

        return $value ?? $def;
    }

    function getAll()
    {
        $this->load();
        return $this->settings ?: [];
    }

    function fetchDbSettings()
    {
        $result = [];
        $fields = [];

        if (Loader::includeModule('askaron.settings') && class_exists('\CAskaronSettings')) {
            $fields += \CAskaronSettings::GetFields();
        }

        foreach ($fields as $name => $value) {
            $name = preg_replace('/UF\_/', '', $name);
            $result['SETTING_' . $name] = $value;
        }

        $result += \Bitrix\Main\Config\Option::getForModule('grain.customsettings') ?: [];

        return $result;
    }

    function load()
    {
        if (!isset($this->settings)) {
            $this->settings = $this->fetchDbSettings();
        }
        return $this;
    }

    function getClientPublicSettings()
    {
        $result = [];
        $settings = $this->container->getSettingsService()->getAll();
        $keys = $this->container->applyFiltersCached('frontend:settings.keys', []);
        foreach ($keys as $key => $info) {
            if (is_numeric($key)) {
                $clientKey = $info;
            } else if (is_string($info)) {
                $clientKey = $info . '.' . $key;
            } else {
                continue;
            }
            $result[$clientKey] = $settings[$key];
        }
        return $result;
    }
}


