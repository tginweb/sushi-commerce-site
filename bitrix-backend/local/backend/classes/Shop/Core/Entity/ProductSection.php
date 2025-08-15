<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\SectionModel;

class ProductSection extends SectionModel
{
    public static function getElementModelClass()
    {
        return Product::class;
    }
}
