<?php

namespace Main\Api\Controller;

use League\Route;
use Main\DI\Containerable;
use Main\Model\Response;

class BaseController
{
    use Containerable;

    public $module;

    public function createResponse()
    {
        return new Response();
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function mapRoute($method, $path, $handler)
    {
        $this->getRouter()->map($method, $path, $handler);
        return $this;
    }

    /**
     * @return Route\Router
     */
    function getRouter()
    {
        return $this->container->getRouter();
    }



    public function mapAdminRoute($method, $path, $handler)
    {
        $this->getRouter()->map($method, '/bxadmin' . $path, $handler);
        return $this;
    }

    public function getRequestBodyJsonParsed()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return $data;
    }
}
