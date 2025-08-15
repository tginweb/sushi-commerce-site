<?php

namespace Company\Core\Graphql;

use Company\Core\Entity\Office;
use Main\Graphql\Type\IBlock\ElementInputType;

class OfficeInput extends ElementInputType
{
    const NAME = 'CompanyOfficeInput';

    static function iblockId()
    {
        return Office::getIblockIdOrThrow();
    }
}
