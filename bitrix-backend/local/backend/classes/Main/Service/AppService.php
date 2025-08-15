<?php

namespace Main\Service;

use Main\DI\Request;

class AppService extends BaseService
{
    function register($scopes = [])
    {
    }

    function getProductionHost()
    {
        return $this->container->getConfigService()->get('APP.PROD_HOST', $_SERVER['HTTP_HOST']);
    }

    function isAdmin()
    {
        return $this->hasScope('admin');
    }

    function hasScope($scope)
    {
        $app = Request::getApp();
        return in_array($scope, $app->registerScopes);
    }
}
