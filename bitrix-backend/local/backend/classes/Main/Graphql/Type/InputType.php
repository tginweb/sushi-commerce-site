<?php

namespace Main\Graphql\Type;

use GraphQL\Type\Definition\InputObjectType as VendorType;

class InputType extends VendorType
{
    const NAME = null;

    public function __construct($config = [])
    {
        $config += [
            'name' => static::NAME,
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public static function getTypeName()
    {
        return static::NAME;
    }
}
