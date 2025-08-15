<?php

namespace Company\Pub;

use Company\Core\Entity\Office;
use Company\Core\Graphql\OfficeFilterInputType;
use Company\Core\Graphql\OfficeInput;
use Company\Core\Graphql\OfficeType;
use Company\Pub\Api\VacancyResolver;
use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            ElementResolversGenerator::create('company_office_')
                ->setModelClass(Office::class)
                ->setModelTypeClass(OfficeType::class)
                ->setModelInputTypeClass(OfficeInput::class)
                ->setFilterTypeClass(OfficeFilterInputType::class)
                //->cache()
                ->addQueries([
                    'single',
                    'list',
                    'recordset'
                ])
                ->addMutations([
                    'update'
                ])
        );

        $router->addResolversGenerator(
            VacancyResolver::create()
                //->cache()
                ->addMutations([
                    'order'
                ])
                ->addQueries([
                    'single',
                    'list',
                    'recordset'
                ])
        );
    }
}
