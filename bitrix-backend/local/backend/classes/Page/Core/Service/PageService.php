<?php

namespace Page\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Page\Core\Entity\Page;

class PageService extends BaseService
{
    function OnAfterIBlockElementUpdate(&$arFields)
    {
        $iblockIds = Page::iblockId();
        if (!in_array($arFields['IBLOCK_ID'], $iblockIds)) return;
        static::instance()->saveRoutes();
    }

    function saveRoutes()
    {
        $optionRoutesStorageUri = $this->container->getConfigService()->get('PAGE.ROUTES_STORAGE_URI');
        if ($optionRoutesStorageUri) {
            $routes = [];
            $elements = Page::query()->getList();
            foreach ($elements as $element) {
                $routeData = $element->getRouteData();
                $routes[$routeData['PATH']] = $routeData;
            }
            // file_put_contents($optionRoutesStorageUri, json_encode($routes, JSON_PRETTY_PRINT));
        }
    }
}



