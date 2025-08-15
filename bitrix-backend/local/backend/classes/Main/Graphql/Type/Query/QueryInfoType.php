<?php

namespace Main\Graphql\Type\Query;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class QueryInfoType extends ObjectType
{
    const NAME = 'QueryInfo';

    public function getFieldsInfo()
    {
        return [
            'total' => Types::int(),
            'page' => Types::int(),
            'pages' => Types::int(),
            'limit' => Types::int(),
            'nextPage' => Types::int(),
        ];
    }

    public function resolveField($parent, $args, $context, ResolveInfo $info)
    {
        return $parent[$info->fieldName];
    }
}
