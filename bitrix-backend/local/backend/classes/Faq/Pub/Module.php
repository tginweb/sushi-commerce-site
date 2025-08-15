<?php

namespace Faq\Pub;

use Faq\Core\Entity\FaqQuestion;
use Faq\Core\Graphql\FaqQuestionType;
use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            ElementResolversGenerator::create('faq_element_')
                ->setModelClass(FaqQuestion::class)
                ->setModelTypeClass(FaqQuestionType::class)
                //->cache()
                ->addQueries([
                    'single',
                    'list',
                    'recordset'
                ])
        );
    }
}
