<?php

namespace Search\Pub\Api;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Search\Core\Entity\SearchResult;
use Search\Core\Entity\SearchSuggestion;
use Search\Core\Graphql\SearchSuggestionType;

class Resolver extends ResolversGenerator
{
    public $ns = 'search_';

    function getQueryMap()
    {
        return [
            'suggestions_popular' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(SearchSuggestionType::class)),
                    'resolve' => [$this, 'querySuggestionsPopular']
                ];
            },
            'suggestions' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(SearchSuggestionType::class)),
                    'args' => [
                        'query' => Types::string(),
                    ],
                    'resolve' => [$this, 'querySearchSuggestions']
                ];
            },
        ];
    }

    function querySuggestionsPopular($rootValue, $args)
    {
        return SearchSuggestion::query()
            ->withViewList()
            ->getList();
    }

    function querySearchSuggestions($rootValue, $args)
    {

        $query = mb_strtolower(trim($args['query']));

        $queryWords = preg_split('/\s+/', $query);

        $searchResult = $this->container->getCatalogService()->searchSuggestions($args['query']);

        //die(\Safe\json_encode($searchResult['rowsByModuleBundle']));

        $result = [];

        $textSuggestions = [];

        /** @var SearchResult $item */
        foreach ($searchResult['rowsByModule']['iblock'] as $item) {

            $key = mb_strtolower($item['TITLE']);
            $result[$key] = [
                'label' => $item['TITLE'],
                'value' => $item['TITLE'],
                'data' => $item->getClientData() + [
                        'type' => 'entity'
                    ]
            ];

            $itemWords = preg_split('/\s+/', $item['TITLE']);
            foreach ($queryWords as $i => $queryWord) {
                $itemWord = $itemWords[$i];
                if (preg_match('/' . $queryWord . '/isu', $itemWord)) {
                    if (!$result[mb_strtolower($itemWord)]) {
                        $textSuggestions[mb_strtolower($itemWord)] = $itemWord;
                    }
                    break;
                }
            }
        }

        if (!empty($textSuggestions)) {
            foreach ($textSuggestions as $textSuggestion) {
                array_unshift($result, [
                    'label' => $textSuggestion,
                    'value' => $textSuggestion,
                    'data' => [
                        'type' => 'search'
                    ]
                ]);
            }
        }

        /*
        array_unshift($result, [
            'label' => 'ТЭГ салаты',
            'value' => 'ТЭГ салаты',
            'data' => [
                "entityRole" => "element",
                "entityTitle" => null,
                "entityTypeCode" => "catalog_tag",
                "entityTypeId" => "56",
                "entityId" => "877995",
                "entityTypeName" => "Теги товаров",
                "hint" => 'в составе',
                "type" => "entity",
            ]
        ]);
        */

        return array_values($result);
    }
}



