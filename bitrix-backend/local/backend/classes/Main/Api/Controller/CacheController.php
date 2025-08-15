<?php

namespace Main\Api\Controller;

use Bitrix\Main\Event;
use function TG\Main\Api\Controller\LocalRedirect;

class CacheController extends BaseController
{
    function __construct()
    {
        $router = $this->getRouter();

        $router->map('GET', '/cache/clear-all', [$this, 'mutationCacheClearAll']);
    }

    function mutationCacheClearAll()
    {
        $service = $this->container->getCacheService();

        $service->clearAll();

        $response = $this->createResponse();

        $event = new Event('tgin.main', 'cache-clear', $_REQUEST);
        $event->send();

        $this->container->doAction('main:cache-clear', $_REQUEST);


        if ($_REQUEST['dest'])
            LocalRedirect($_REQUEST['dest'], true);

        return $response->getRestJson();
    }
}
