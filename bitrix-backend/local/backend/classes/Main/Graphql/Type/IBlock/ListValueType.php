<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class ListValueType extends EntityType
{
    const NAME = 'ListValue';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::nonNull(Types::int()),
            'VALUE' => Types::nonNull(Types::string()),
            'CODE' => Types::nonNull(Types::string()),
        ];
    }

    public function resolve_CODE($parent, $args, $ctx)
    {
        return $parent['XML_ID'];
    }
}
