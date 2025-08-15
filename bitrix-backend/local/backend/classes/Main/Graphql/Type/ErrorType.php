<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class ErrorType extends ObjectType
{
    public function __construct($config = [])
    {
        $config += [
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'type' => Types::string(),
            'name' => Types::string(),
            'message' => Types::string(),
            'fieldMessage' => Types::string(),
            'rel' => Types::JSON(),
            'alert' => Types::boolean(),
        ];
    }
}
