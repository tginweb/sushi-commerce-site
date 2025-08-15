<?php

namespace Main\Graphql\Type\Query;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class QueryNavInputType extends InputType
{
    const NAME = 'QueryNavInput';

    function getFieldsInfo()
    {
        $arFields = [
            'limit' => [
                'type' => Types::int(),
            ],
            'page' => [
                'type' => Types::int(),
            ],
            'sort' => [
                'type' => Types::string(),
            ],
            'sortField' => [
                'type' => Types::string(),
            ],
            'asc' => [
                'type' => Types::boolean(),
            ],
            'sortAscending' => [
                'type' => Types::boolean(),
            ],
            'postSort' => [
                'type' => Types::string(),
            ],
            'postSortAsc' => [
                'type' => Types::boolean(),
            ],
            'postLimit' => [
                'type' => Types::int(),
            ],
        ];

        return $arFields;
    }
}
