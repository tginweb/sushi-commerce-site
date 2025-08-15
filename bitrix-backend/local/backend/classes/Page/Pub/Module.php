<?php

namespace Page\Pub;

use Main\Lib\Common\BaseModule;
use Page\Core\Entity\Page;
use Page\Core\Graphql\PageType;
use Page\Pub\Api\Resolver;

class Module extends BaseModule
{
    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            Resolver::create()
                ->setModelClass(Page::class)
                ->setModelTypeClass(PageType::class)
                ->cache()
                ->addQueries([
                    'single',
                    'list',
                    'recordset',
                    'route',
                ])
        );
    }
}
