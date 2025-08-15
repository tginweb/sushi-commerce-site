<?php

namespace Main\Service;

class CorsService extends BaseService
{
    function register()
    {
        $this->sendHeaders();
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->onOptionsRequestMethod();
        }
    }

    function sendHeaders()
    {
        $allowDomain = $_SERVER['HTTP_ORIGIN'] ?? '';

        header('Access-Control-Allow-Origin: ' . $allowDomain);
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type, *');
        header('Access-Control-Allow-Credentials: true');

        header('X-Frame-Options: allow-from istu-int:8080');
    }

    function onOptionsRequestMethod()
    {
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }
}

