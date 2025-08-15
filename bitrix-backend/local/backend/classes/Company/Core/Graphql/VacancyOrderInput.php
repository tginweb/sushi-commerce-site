<?php

namespace Company\Core\Graphql;

use Company\Core\Entity\VacancyOrderModel;
use Main\Graphql\Type\IBlock\ElementInputType;

class VacancyOrderInput extends ElementInputType
{
    const NAME = 'VacancyOrderInput';

    static function iblockId()
    {
        return VacancyOrderModel::getIblockIdOrThrow();
    }
}
