<?php

namespace Main\Graphql;

use GraphQL\Type\Definition\FloatType;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;
use Main\Graphql\Enum\DateFormatEnum;
use Main\Graphql\Type\Filter\DateFilterInput;
use Main\Graphql\Type\Filter\IntFilterInput;
use Main\Graphql\Type\Filter\StringFilterInput;
use Main\Graphql\Type\Scalar\EmailType;
use Main\Graphql\Type\Scalar\JsonType;
use Main\Graphql\Type\Scalar\UrlType;
use Main\Registry;

class Types extends Registry
{
    public static function getNonNull($classname, $resolve = false)
    {
        return static::nonNull(static::get($classname, $resolve));
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }

    public static function BooleanFilter(): callable
    {
        return static::get(StringFilterInput::class);
    }

    public static function DateFilter(): callable
    {
        return static::get(DateFilterInput::class);
    }

    public static function IntFilter(): callable
    {
        return static::get(IntFilterInput::class);
    }

    public static function StringFilter(): callable
    {
        return static::get(StringFilterInput::class);
    }

    public static function email(): callable
    {
        return static::get(EmailType::class);
    }

    public static function url(): callable
    {
        return static::get(UrlType::class);
    }

    public static function JSON(): callable
    {
        return static::get(JsonType::class);
    }

    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return IntType
     */
    public static function int()
    {
        return Type::int();
    }

    public static function datetime($required = false)
    {
        return [
            'args' => [
                'format' => self::string(),
                'formatType' => self::get(DateFormatEnum::class),
            ],
            'type' => $required ? new NonNull(self::JSON()) : self::JSON(),
        ];
    }

    /**
     * @return array
     */
    public static function date($required = false, $resolver = null)
    {
        return self::datetime($required);
    }

    /**
     * @return StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNullListOf($type)
    {
        return static::nonNull(self::listOf($type));
    }

    /**
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }


}
