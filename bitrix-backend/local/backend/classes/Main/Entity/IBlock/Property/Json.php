<?php

namespace Main\Entity\IBlock\Property;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\IBlock\PropertyModel;
use Main\Graphql\Types;

class Json extends PropertyModel
{

    function getGglInputClass()
    {
        return Types::JSON();
    }

    function getGglTypeClass()
    {
        return Types::JSON();
    }

    function gqlResolve(ElementModel $parent, $args, $context, ResolveInfo $info)
    {
        $val = $parent->getProp($info->fieldName);
        return $this->prepareValue($val);
    }

    function prepareValue($value)
    {
        if (is_string($value)) {
            return json_decode($value);
        }
    }
}



