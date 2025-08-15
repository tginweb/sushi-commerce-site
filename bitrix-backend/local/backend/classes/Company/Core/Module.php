<?php

namespace Company\Core;

use Main\Entity\IBlock\Property\Coordinates;
use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);
        $this->container->addFilter('main:entity.types', [$this, 'registerEntityTypes']);
        $this->container->addFilter('iblock:properties', [$this, 'registerIblockProperties']);
    }

    function registerIblockProperties($data)
    {
        $data['office.COORDINATES'] = [
            'class' => Coordinates::class
        ];
        return $data;
    }

    function registerTypes()
    {
        Types::types([
            'CompanyOffice' => \Company\Core\Graphql\OfficeType::class,
            'CompanyVacancy' => \Company\Core\Graphql\VacancyType::class,
        ]);
    }
}
