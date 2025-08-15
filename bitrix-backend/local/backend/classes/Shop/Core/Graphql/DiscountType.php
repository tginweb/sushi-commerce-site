<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Shop\Core\Entity\Discount;

class DiscountType extends ElementType
{
    const NAME = 'Discount';

    static function getModelClass()
    {
        return Discount::class;
    }

    public function resolve_CONDITIONS(Discount $parent, $args, $ctx)
    {
        return $parent->getConditions();
    }
}
