<?php

namespace Main\Graphql\Type\Query;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class QueryCacheInputType extends InputType
{
    const NAME = 'QueryCacheInput';

    function getFieldsInfo()
    {
        $arFields = [
            'enable' => [
                'type' => Types::boolean(),
            ],
        ];

        return $arFields;
    }
}
