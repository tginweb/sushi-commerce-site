<?php

namespace Boot;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Executor;
use TG\Main;

class ApiGraphQL extends Api
{
    public $requestData;
    public $requestRaw;

    function __construct($requestRaw)
    {
        $this->requestRaw = $requestRaw;
        if (isset($GLOBALS['GRAPHQL_REQUEST_DATA'])) {
            $this->requestData = $GLOBALS['GRAPHQL_REQUEST_DATA'];
        } else {
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $this->requestData = json_decode($this->requestRaw, true) ?: [];
            } else {
                $this->requestData = $_REQUEST;
            }
        }
    }

    function getRequestCacheItem()
    {
        if (!isset($this->requestCacheItem)) {
            if (
                !empty($this->requestData['variables']['_scope'])
                && ($this->requestData['variables']['_scope'] === 'app')
                && ($cacheAdapter = $this->getCacheAdapter())
                && false
            ) {
                $cacheId = 'route.' . md5($this->requestRaw);
                $this->requestCacheItem = $cacheAdapter->getItem($cacheId);
            } else {
                $this->requestCacheItem = false;
            }
        }
        return $this->requestCacheItem;
    }

    function getCachedResponse()
    {
        $cacheItem = $this->getRequestCacheItem();
        if ($cacheItem && $cacheItem->isHit()) {
            return $cacheItem->get();
        }
    }

    function boot()
    {
        $cacheItem = $this->getRequestCacheItem();

        Loader::includeModule('main');

        $container = \Main\DI\Containerable::container();

        Executor::setImplementationFactory([\Main\Graphql\Executor\ReferenceExecutor::class, 'create']);

        $gqlService = $container->getGraphqlService();

        try {
            $gqlService->build();
            $output = $gqlService->processRequest($this->requestData);
            $httpStatus = 200;
        } catch (\Throwable $error) {
            $output = (new ExecutionResult(null, [$error]))->toArray($gqlService->getDebugFlag());
            //$httpStatus = 500;
            //StandardServer::send500Error($error, $gqlService->getDebugFlagOutside($error), true);
        }

        $outputStr = json_encode($output);

        header('Content-Type: application/json', true, $httpStatus);

        $app = Application::getInstance();

        $context = $app->getContext();

        $context->getResponse()->writeHeaders();

        $app->getContext()->getResponse()->setContent($outputStr);

        //$application = \Bitrix\Main\Application::getInstance();
        //$connection = $application->getConnection();

        if ($cacheItem) {
            $cacheItem->set($outputStr);
            $cacheItem->expiresAfter(1000);
            $this->getCacheAdapter()->save($cacheItem);
        }

        $app->end();
    }
}



