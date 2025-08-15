<?php

namespace Faq\Core\Graphql;

use Faq\Core\Entity\FaqQuestion;
use Main\Graphql\Type\IBlock\ElementType;

class FaqQuestionType extends ElementType
{
    const NAME = 'FaqQuestion';

    static function iblockId()
    {
        return FaqQuestion::getIblockIdOrThrow();
    }
}
