<?php

namespace Main\Graphql\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Types;

class OtpType extends ObjectType
{
    const NAME = 'Otp';

    public function getFieldsInfo()
    {
        return [
            'SID' => Types::string(),
            'HITS_LEFT' => Types::int()
        ];
    }

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        return $element[$info->fieldName];
    }
}
