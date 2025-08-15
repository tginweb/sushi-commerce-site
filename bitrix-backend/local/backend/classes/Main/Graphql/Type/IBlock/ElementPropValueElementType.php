<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class ElementPropValueElementType extends EntityType
{
    const NAME = 'ElementPropValueElement';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'ELEMENT' => Types::get(ElementType::class)
        ];
    }

    function resolve_ELEMENT($parent, $args, $context)
    {
        $id = $parent['ID'];
        return !empty($id) ? $context['dataloader']['element']->load($id) : null;
    }
}
