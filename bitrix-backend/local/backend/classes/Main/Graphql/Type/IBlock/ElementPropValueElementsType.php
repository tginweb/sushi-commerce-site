<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class ElementPropValueElementsType extends EntityType
{
    const NAME = 'ElementPropValueElements';

    public function getFieldsInfo()
    {
        return [
            'IDS' => Types::int(),
            'ELEMENTS' => Types::get(ElementType::class)
        ];
    }

    function resolve_ELEMENTS($parent, $args, $context)
    {
        $ids = $parent['IDS'];
        return !empty($ids) ? $context['dataloader']['element']->loadMany($ids) : [];
    }
}
