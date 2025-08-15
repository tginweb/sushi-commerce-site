<?php

namespace Main;

use GraphQL\Type\Definition\ObjectType;
use Main\DI\Containerable;
use Main\Error\UserError;
use Main\Graphql\Enum\BaseEnum;
use Main\Graphql\Type\SubscriptionType;
use Main\Graphql\Types;
use Protobuf\Exception;

class Registry
{
    use Containerable;

    // Class by Type
    public static $types = [];
    public static $typesByClass = [];

    public static $typeInstance = [];
    public static $classInstance = [];

    public static $typesInclude = [];

    const LAZY_LOAD = true;

    static $data = [
        'enum' => [],
        'error' => [],
        'interface' => [],
        'dataloader' => [],
        'field' => [],
        'typeInclude' => []
    ];

    public static function enum($key, $cls)
    {
        static::type($key, $cls);
        static::$data['enum'][$key] = $cls;
    }

    public static function enums($map)
    {
        foreach ($map as $type => $class) {
            static::enum($type, $class);
        }
    }

    public static function enumInstance($type, $values)
    {
        return static::typeInstance($type, function () use ($type, $values) {
            return new BaseEnum([
                'name' => $type,
                'values' => $values
            ]);
        });
    }

    public static function error($key, $cls)
    {
        static::type($key, [$cls, 'getGraphQlType'], true);
        static::$data['error'][$key] = $cls;
    }

    public static function errors($map)
    {
        foreach ($map as $key => $cls) {
            static::error($key, $cls);
        }
    }

    public static function dataloader($key, $cls)
    {
        //static::type($key, $cls);
        static::$data['dataloader'][$key] = $cls;
    }

    function getRegistry($type)
    {
        return static::$data[$type];
    }

    public static function hasType($typename)
    {
        return static::$types[$typename] ?? false;
    }

    public static function get($classname, $resolve = false)
    {
        return static::LAZY_LOAD && !$resolve ? function () use ($classname) {
            return static::getByClassName($classname);
        } : static::getByClassName($classname);
    }

    public static function getType($typename, $resolve = false)
    {
        if ($typename === 'Subscription')
            return Types::get(SubscriptionType::class, true);

        return static::LAZY_LOAD && !$resolve ? function () use ($typename) {
            return static::getByTypeName($typename);
        } : static::getByTypeName($typename);
    }

    public static function getByClassName($className)
    {
        $instance = static::$classInstance[$className];
        if (!isset($instance)) {
            if (class_exists($className)) {
                $instance = new $className();
            } else {
                //die('NOT FOUND CLASS TYPE: ' . $className);
                throw new UserError('NOT FOUND CLASS TYPE: ' . $className);
            }
            $typeName = static::$typesByClass[$className];
            if ($typeName) {
                static::$typeInstance[$typeName] = $instance;
            }
            static::$classInstance[$className] = $instance;
        }
        return $instance;
    }

    public static function getByTypeName($typeName)
    {
        $instance = null;

        if (isset(static::$typeInstance[$typeName])) {
            return static::$typeInstance[$typeName];
        }

        if (method_exists(get_called_class(), $typeName)) {
            $instance = self::{$typeName}();
        } else {
            $generator = static::$types[$typeName];
            if (isset($generator)) {
                if (is_array($generator) && is_callable($generator)) {
                    $instance = call_user_func($generator);
                } else {
                    $instance = self::get($generator, true);
                }
                static::$typeInstance[$typeName] = $instance;
            }
        }

        if (!$instance) {
            //debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            throw new \Exception("Unknown type: " . $typeName);
        }

        if (is_callable($instance)) {
            $instance = $instance();
        }

        return $instance;
    }

    public static function ensureType($typeName, $fields = [])
    {
        if (!isset(self::$typeInstance[$typeName])) {
            self::$typeInstance[$typeName] = new ObjectType([
                'name' => $typeName,
                'fields' => $fields
            ]);
        }
        return self::$typeInstance[$typeName];
    }

    public static function type($typeName, $cls, $forceInclude = false)
    {
        if ($forceInclude)
            static::$typesInclude[$typeName] = $cls;

        if (!$cls) {
            throw new \Exception('Type "' . $typeName . '" classname is required');
        }

        static::$types[$typeName] = $cls;

        if (is_string($cls)) {
            static::$typesByClass[$cls] = $typeName;
        }
    }

    public static function types($map)
    {
        foreach ($map as $typeName => $cls) {
            static::type($typeName, $cls);
        }
    }

    public static function getDataloaders()
    {
        return static::$data['dataloader'];
    }

    public static function typeInstance($type, $instance)
    {
        if (!isset(static::$typeInstance[$type])) {
            if (is_callable($instance)) {
                $instance = $instance();
            }
            static::$typeInstance[$type] = $instance;
        }
        return static::$typeInstance[$type];
    }
}
