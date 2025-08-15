<?php

namespace Page\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;
use Main\Lib\Common\Invoker;
use Page\Core\Service\PageService;
use function TG\Page\Core\AddEventHandler;

class Module extends BaseModule
{
    function __construct()
    {
        $container = $this->container;
        $container->define(PageService::class);
        AddEventHandler("iblock", "OnAfterIBlockElementUpdate", new Invoker([PageService::class, 'OnAfterIBlockElementUpdate']));
    }

    function registerTypes()
    {
        Types::types([
            'PageElement' => \Page\Core\Graphql\PageType::class,
            'PageContentChunk' => \Page\Core\Graphql\ContentChunkType::class,
            'PageDataChunk' => \Page\Core\Graphql\DataChunkType::class,
        ]);
    }
}
