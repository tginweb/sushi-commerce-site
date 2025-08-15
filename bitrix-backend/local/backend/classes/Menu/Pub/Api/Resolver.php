<?php

namespace Menu\Pub\Api;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Menu\Core\Graphql\MenuType;

class Resolver extends ResolversGenerator
{
    public $ns = 'menu_';

    function getQueryMap()
    {
        return [
            'menus' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::listOf(Types::get(MenuType::class)),
                  //  'cache' => true,
                    'resolve' => [$this, 'queryMenus'],
                ];
            },
        ];
    }

    function queryMenus($rootValue, $args)
    {
        return $this->container->getMenuService()->getResultMenus($this->container->getUser());
    }
}



