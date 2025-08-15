<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketBuildItemType extends ObjectType
{
    const NAME = 'BasketBuildItem';

    function getFieldsInfo()
    {
        return [
                'PRODUCT_ID' => Types::int(),
                'QUANTITY' => Types::int(),
                'ELEMENT' => Types::getType('ProductElement'),
            ] + parent::getFieldsInfo();
    }

    public function resolve_ELEMENT($parent, $args, $ctx)
    {
        $id = $parent['PRODUCT_ID'];
        return !empty($id) ? $ctx['dataloader']['element']->load($id) : null;
    }
}
