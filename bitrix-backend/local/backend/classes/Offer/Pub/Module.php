<?php

namespace Offer\Pub;

use Main\Lib\Common\BaseModule;
use Offer\Core\Entity\OfferModel;
use Offer\Core\Graphql\OfferType;
use Offer\Pub\Api\Resolver;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);
    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            Resolver::create()
                ->setModelClass(OfferModel::class)
                ->setModelTypeClass(OfferType::class)
                ->cache()
                ->addQueries([
                    'single',
                    'list',
                    'recordset',
                    'user_list',
                    'common_list'
                ])
        );
    }
}
