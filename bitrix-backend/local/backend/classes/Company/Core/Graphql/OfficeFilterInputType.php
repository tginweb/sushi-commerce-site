<?php

namespace Company\Core\Graphql;

use Company\Core\Entity\Office;
use Main\Graphql\Type\IBlock\ElementFilterInputType;

class OfficeFilterInputType extends ElementFilterInputType
{
    const NAME = 'OfficeFilterInput';

    function getQuery()
    {
        return Office::query();
    }
}
