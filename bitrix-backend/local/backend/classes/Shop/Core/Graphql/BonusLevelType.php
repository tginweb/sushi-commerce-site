<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Shop\Core\Entity\BonusLevel;

class BonusLevelType extends ElementType
{
    const NAME = 'BonusLevel';

    static function getModelClass()
    {
        return BonusLevel::class;
    }
}
