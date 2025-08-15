<?php

namespace Menu\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    const REGISTER_API_ENABLED = false;

    function register($scopes = [])
    {
        $this->container->define(Service::class);

        $this->container->addFilter('menu:menus', [$this, 'buildMenu']);

        parent::register($scopes);
    }

    public function buildMenu($menus)
    {
        return $menus;
    }

    function registerTypes()
    {
        Types::types([
            'Menu' => \Menu\Core\Graphql\MenuType::class,
            'MenuMobile' => \Menu\Core\Graphql\MenuMobileType::class,
            'MenuItem' => \Menu\Core\Graphql\MenuItemType::class,
            'MenuItemMobile' => \Menu\Core\Graphql\MenuItemMobileType::class,
        ]);
    }
}



