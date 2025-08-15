<?php

namespace Main\Guard;

use Main\Error\AccessError;
use TG\Main\Request\Error\GuardError;

class UserGuard extends BaseGuard
{
    function access($context = null)
    {
        $user = $this->container->getUser();

        if (!$user)
            return new AccessError('Пользователь не авторизован для доступа', 'UNAUTHENTICATED');

        if (!$user->isAdmin()) {
            if (isset($this->args['userGroup'])) {
                $needUserGroup = (array)$this->args['userGroup'];

                if (!$user->inRoles($needUserGroup)) {
                    return new AccessError('Ошибка прав доступа', 'RESTRICTED');
                }
            }
        }

        return true;
    }
}


