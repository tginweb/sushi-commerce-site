<?php

namespace Event\Pub;

use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);

        $this->container->addFilter('menu:menus', [$this, 'buildMenu']);
    }

    function buildMenu($menus)
    {
        $profilePath = $this->container->getConfigService()->get('USER.PROFILE_PATH');

        $menus['personal']['children'] = array_merge($menus['personal']['children'], [
            [
                'id' => 'notice:notices',
                'label' => 'Уведомления',
                'icon' => 'bell',
                'url' => $profilePath . '/notices',
                'sort' => 20,
                'badge' => [
                    'class' => 'bg-red',
                    'getter' => 'notice:unreadedCount'
                ]
            ],
        ]);

        return $menus;
    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            \Event\Pub\Api\Resolver\ClientEmit::create()->addAll(),
            \Event\Pub\Api\Resolver\ClientNotice::create()->addAll(),
            \Event\Pub\Api\Resolver\ClientPush::create()->addAll(),
        );
    }
}
