<?php

namespace Page\Pub\Api;

use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Graphql\Types;
use Page\Core\Entity\Page;
use Page\Core\Graphql\PageType;

class Resolver extends ElementResolversGenerator
{
    public $ns = 'page_';

    function getQueryMap()
    {
        return parent::getQueryMap() + [
                'route' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::get(PageType::class),
                        'args' => [
                            'URL' => Types::string(),
                        ],
                        'cache' => $this->getQueryCacheConfig($queryName),
                        'resolve' => [$this, 'queryPageRoute'],
                    ];
                },
            ];
    }

    function queryPageRoute($rootValue, $args)
    {
        $url = $args['URL'];
        $url = '/' . trim($url, '/');
        return Page::query()
            ->select('PROPS_ALL')
            ->filter(['CODE' => $url])
            ->withViewList()
            ->first();
    }
}



