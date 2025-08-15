<?php

namespace Main\Graphql\Enum;

use GraphQL\Type\Definition\EnumType;
use TG\Main\Helper;

class BaseEnum extends EnumType
{
    const NAME = null;

    function __construct($fields = [])
    {
        parent::__construct($fields + [
                'name' => static::getTypeName(),
                'values' => static::values()
            ]);
    }

    static function getTypeName()
    {
        if (!static::NAME) {
            $parts = preg_split('/\\\\/', static::class);
            return $parts[count($parts) - 1];
        } else {
            return static::NAME . 'Enum';
        }
    }

    static function values()
    {
        return array_keys(static::options());
    }

    static function options()
    {
        return [];
    }

    static function getOptions()
    {
        return \Main\Helper\Format::toOptions(static::options(), 'value', 'name', true);
    }
}
