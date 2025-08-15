<?php

namespace Main\Service;

use Main\Bitrix\UserField\UserGroup;
use TG\Main\Graphql\Type\User\UserConnectionType;

class UserService extends BaseService
{
    function register($scopes = [])
    {
        UserGroup::init();

        $this->getHooks()->add_filter('menu:menus', [$this, 'buildMenu']);
        $this->getHooks()->add_filter('main:commands', [$this, 'buildCommands']);

        register_shutdown_function([$this, 'onShutdown']);
    }

    function buildMenu($menus)
    {
        $profilePath = $this->container->getConfigService()->get('USER.PROFILE_PATH');

        $menus['personal'] = [
            'code' => 'personal',
            'children' => []
        ];

        $menus['personal']['children'] = [
            [
                'id' => 'user:profile',
                'icon' => 'person',
                'label' => 'Профиль',
                'url' => $profilePath . '/dashboard',
                'sort' => -100
            ],
            [
                'id' => 'user:logout',
                'label' => 'Выход',
                'url' => $profilePath . '/logout',
                'icon' => 'logout',
                'params' => [
                    'page_hide' => true
                ],
                'sort' => 100
            ],
        ];

        return $menus;
    }

    public function buildCommands($commands)
    {
        $profilePath = $this->container->getConfigService()->get('USER.PROFILE_PATH');

        $commands['user:profile'] = [
            'type' => 'vrouter',
            'path' => $profilePath . '/menu',
            'phone' => [
                'type' => 'vrouter',
                'path' => $profilePath . '/menu',
            ]
        ];

        $commands['user:login'] = [
            'type' => 'vrouter',
            'path' => $profilePath . '/login'
        ];

        $commands['user:logout'] = [
            'type' => 'vrouter',
            'path' => $profilePath . '/logout'
        ];

        $commands['user:profile.edit'] = [
            'type' => 'vrouter',
            'path' => $profilePath . '/profile-edit'
        ];

        return $commands;
    }

    function confirmPhoneNeed()
    {
        return $this->container->getConfigService()->get('USER.CONFIRM_PHONE_NEED', true);
    }

    function confirmEmailNeed()
    {
        return $this->container->getConfigService()->get('USER.CONFIRM_EMAIL_NEED', true);
    }

    function getAutoCreateEmailDomain()
    {
        $domain = $this->container->getConfigService()->get('USER.AUTO_CREATE_EMAIL_DOMAIN');

        if (!$domain) {
            $host = preg_replace('/www./', '', $_SERVER["HTTP_HOST"]);
            $domain = 'vuser.' . $host;
        }

        return $domain;
    }
}


