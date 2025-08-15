<?php

namespace Review\Pub;

use Main\Lib\Common\BaseModule;
use Review\Core\Entity\Review;
use Review\Core\Graphql\ReviewType;
use Review\Pub\Api\Resolver\Resolver;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);

        $this->container->addFilter('menu:menus', [$this, 'buildMenu']);
    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            Resolver::create()
                ->setModelClass(Review::class)
                ->setModelTypeClass(ReviewType::class)
                // ->cache()
                ->addQueries([
                    'list',
                ])
                ->addMutations([
                    'service_review',
                    'order_guest_review',
                ])
        );
    }

    function buildMenu($menus)
    {
        $profilePath = $this->container->getConfigService()->get('USER.PROFILE_PATH');

        return $menus;

        $menus['personal']['children'] = array_merge($menus['personal']['children'], [
            [
                'id' => 'review:reviews',
                'label' => 'Мои отзывы',
                'icon' => 'star',
                'url' => $profilePath . '/reviews',
                'params' => [
                    'page_hide' => true
                ],
                'sort' => 50
            ],
        ]);

        return $menus;
    }
}
