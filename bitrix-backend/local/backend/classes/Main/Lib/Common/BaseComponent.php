<?php

namespace Main\Lib\Common;

class BaseComponent extends Base
{
    const REGISTER_API_ENABLED = true;
    public $registerScopes = [];

    function getRegisterScopes()
    {
        return $this->registerScopes;
    }

    function setRegisterScopes($scopes = [])
    {
        $this->registerScopes = $scopes;
    }

    function register($scopes = [])
    {
        if (!empty($scopes))
            $this->setRegisterScopes($scopes);

        $this->registerEvents();
        $this->registerTypes();
        $this->registerApi();

        if ($this->container->isAdminSection()) {
            $this->registerAdmin();
        }

        return $this;
    }

    function registerApi()
    {
        $this->registerApiResolvers();
        $this->registerApiControllers();
        return $this;
    }

    function registerTypes()
    {

    }

    function registerApiControllers()
    {

    }

    function registerApiResolvers()
    {

    }


    function registerEvents()
    {

    }

    function registerAdmin()
    {

    }
}
