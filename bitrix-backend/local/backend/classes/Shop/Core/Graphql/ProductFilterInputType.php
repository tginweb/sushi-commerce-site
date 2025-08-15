<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementFilterInputType;
use Shop\Core\Entity\Product;

class ProductFilterInputType extends ElementFilterInputType
{
    const NAME = 'ProductFilterInput';

    function getQuery()
    {
        return Product::query();
    }
}
