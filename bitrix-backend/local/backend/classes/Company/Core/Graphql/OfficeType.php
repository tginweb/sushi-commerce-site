<?php

namespace Company\Core\Graphql;

use Company\Core\Entity\Office;
use Main\Graphql\Type\IBlock\ElementType;

class OfficeType extends ElementType
{
    const NAME = 'CompanyOffice';

    public static function getModelClass()
    {
        return Office::class;
    }
}
