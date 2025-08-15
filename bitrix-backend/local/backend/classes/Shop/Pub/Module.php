<?php

namespace Shop\Pub;

use Bitrix;
use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Lib\Common\BaseModule as BaseModule;
use Shop\Core\Entity\BonusLevel;
use Shop\Core\Entity\Product;
use Shop\Core\Graphql\BonusLevelType;
use Shop\Core\Graphql\ProductType;
use Shop\Pub\Api\Resolver\ClientCardResolver;
use Shop\Pub\Api\Resolver\DeliveryResolver;
use Shop\Pub\Api\Resolver\OrderResolver;
use Shop\Pub\Api\Resolver\ProfileResolver;
use Shop\Pub\Api\Resolver\SaleResolver;
use Shop\Pub\Api\Resolver\VorderResolver;

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
            ElementResolversGenerator::create('catalog_product_')
                ->setModelClass(Product::class)
                ->setModelTypeClass(ProductType::class)
                ->cache()
                ->addQueries([
                    'single',
                    'list',
                    'recordset'
                ])
        );

        $router->addResolversGenerator(
            OrderResolver::create()->addAll(),
            VorderResolver::create()->addAll(),
            ProfileResolver::create()->addAll(),
            ClientCardResolver::create()->addAll(),
            DeliveryResolver::create()->addAll(),
            SaleResolver::create()->addAll(),

            ElementResolversGenerator::create('sale_bonus_level_')
                ->setModelClass(BonusLevel::class)
                ->setModelTypeClass(BonusLevelType::class)
                //->cache()
                ->addQueries([
                    'list',
                ])
        );
    }

    function registerApiControllers()
    {

    }
}



