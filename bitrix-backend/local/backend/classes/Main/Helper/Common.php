<?php

namespace Main\Helper;

class Common
{
    static function toScalar($value)
    {
        if (is_array($value)) {
            return join(',', array_map([static::class, 'toScalar'], $value));
        }
        if (is_numeric($value)) {
            return intval($value);
        }
        return $value;
    }

    static function toArray($value)
    {
       if (!isset($value)) return [];
       return (array)$value;
    }

    static function &getNestedValue(&$array, $parents, &$key_exists = NULL)
    {
        if (is_string($parents)) {
            $parents = explode('.', $parents);
        }
        $ref =& $array;
        foreach ($parents as $parent) {
            if (is_array($ref) && (isset($ref[$parent]) || array_key_exists($parent, $ref))) {
                $ref =& $ref[$parent];
            } else if (is_object($ref) && (isset($ref->{$parent}))) {
                $ref =& $ref->{$parent};
            } else {
                $key_exists = FALSE;
                $null = NULL;
                return $null;
            }
        }
        $key_exists = TRUE;
        return $ref;
    }

    static function setNestedValue(array &$array, $parents, $value, $force = FALSE)
    {
        if (is_string($parents)) {
            $parents = explode('.', $parents);
        }

        $ref = &$array;
        foreach ($parents as $parent) {
            // PHP auto-creates container arrays and NULL entries without error if $ref
            // is NULL, but throws an error if $ref is set, but not an array.
            if ($force && isset($ref) && !is_array($ref)) {
                $ref = array();
            }
            $ref = &$ref[$parent];
        }
        $ref = $value;
    }


    static function &arrayGetNestedValue(array &$array, $parents, &$key_exists = NULL)
    {

        if (is_string($parents)) {
            $parents = explode('.', $parents);
        }

        $ref =& $array;
        foreach ($parents as $parent) {
            if (is_array($ref) && (isset($ref[$parent]) || array_key_exists($parent, $ref))) {
                $ref =& $ref[$parent];
            } else {
                $key_exists = FALSE;
                $null = NULL;
                return $null;
            }
        }
        $key_exists = TRUE;
        return $ref;
    }

    static function arraySetNestedValue(array &$array, $parents, $value, $force = FALSE)
    {
        if (is_string($parents)) {
            $parents = explode('.', $parents);
        }

        $ref = &$array;
        foreach ($parents as $parent) {
            // PHP auto-creates container arrays and NULL entries without error if $ref
            // is NULL, but throws an error if $ref is set, but not an array.
            if ($force && isset($ref) && !is_array($ref)) {
                $ref = array();
            }
            $ref = &$ref[$parent];
        }
        $ref = $value;
    }

    static function getStaticData($data, $context = [], $container = null)
    {

        $result = [];

        foreach ($data as $key => $val) {

            if (is_callable($val)) {
                $val = $val($context, $container);
            } else if (is_string($val)) {
                if (strpos($val, '#') !== false) {
                    $val = preg_replace_callback('/\#([^\#]+)\#/', function ($mt) use ($context) {
                        return $context[$mt[1]];
                    }, $val);
                }
            }

            $result[$key] = $val;
        }

        return $result;
    }

    static function objectToArrayDeep($object)
    {
        if ($object) {
            return json_decode(json_encode($object), true);
        } else {
            return $object;
        }
    }

    static function getCallbackOutput($cb)
    {
        ob_start();
        $cb();
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }
}
