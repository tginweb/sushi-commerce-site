<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class PaysystemWrapperType extends ObjectType
{
    const NAME = 'PaysystemWrapper';

    public function getFieldsInfo()
    {
        return [
            'IS_INNER' => Types::boolean(),
            'IS_ONLINE' => Types::boolean(),
            'IS_BILL' => Types::boolean(),
        ];
    }

    public function resolve_ID($parent, $args, $ctx)
    {
        return $parent->id;
    }

    public function resolve_IS_INNER($parent, $args, $ctx)
    {
        return $parent->isInner();
    }

    public function resolve_IS_ONLINE($parent, $args, $ctx)
    {
        return $parent->isOnline();
    }

    public function resolve_IS_BILL($parent, $args, $ctx)
    {
        return $parent->isBill();
    }
}
