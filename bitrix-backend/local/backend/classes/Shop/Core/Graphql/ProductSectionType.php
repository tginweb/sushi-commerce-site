<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\SectionType;
use Shop\Core\Entity\ProductSection;

class ProductSectionType extends SectionType
{
    const NAME = 'ProductSection';

    public function getModelClass()
    {
        return ProductSection::class;
    }
}
