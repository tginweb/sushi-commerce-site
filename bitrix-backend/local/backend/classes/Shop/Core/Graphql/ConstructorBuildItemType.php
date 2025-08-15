<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ConstructorBuildItemType extends ObjectType
{
    const NAME = 'ConstructorBuildItem';

    function getFieldsInfo()
    {
        return [
                'ELEMENT_ID' => Types::int(),
                'QUANTITY' => Types::int(),
                'ELEMENT' => Types::get(ConstructorType::class),
            ] + parent::getFieldsInfo();
    }

    public function resolve_ELEMENT($parent, $args, $ctx)
    {
        $id = $parent['ELEMENT_ID'];
        return !empty($id) ? $ctx['dataloader']['element']->load($id) : null;
    }
}
