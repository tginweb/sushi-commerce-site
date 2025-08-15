<?php

namespace Boot;

use Illuminate\Http\Response;
use Laminas\Diactoros\ServerRequestFactory;

class ApiRest extends Api
{
    public $requestUrl;

    function __construct($url)
    {
        $this->requestUrl = $url;
    }

    function boot()
    {
        $container = $this->container();

        $_SERVER['REQUEST_URI'] = $this->requestUrl;

        $routerService = $container->getRouterService();

        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $routerContainer = $routerService->getRouterContainer();
        $matcher = $routerContainer->getMatcher();
        $route = $matcher->match($request);
        if (!$route) {
            echo "No route found for the request.";
            exit;
        }

        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $callable = $route->handler;
        $response = new Response;

        $callable($request, $response);

        $response->sendHeaders();
        $responseString = $response->getContent();

        global $APPLICATION;
        $APPLICATION->FinalActions($responseString);
    }
}



