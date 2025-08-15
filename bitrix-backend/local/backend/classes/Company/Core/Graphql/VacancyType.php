<?php

namespace Company\Core\Graphql;

use Company\Core\Entity\Vacancy;
use Main\Graphql\Type\IBlock\ElementType;

class VacancyType extends ElementType
{
    const NAME = 'CompanyVacancy';

    static function iblockId()
    {
        return Vacancy::getIblockIdOrThrow();
    }

    public function getFieldsInfo()
    {
        return array_merge(parent::getFieldsInfo(), [

        ]);
    }

    public function resolve_url($parent, $args, $ctx)
    {
        /** @var Vacancy $parent */
        return $parent->getUrl();
    }
}
