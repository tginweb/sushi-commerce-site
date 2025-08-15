<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SearchResultType extends ObjectType
{
    const NAME = 'SearchResult';

    function getFieldsInfo()
    {
        return [
                'ELEMENTS' => [
                    'type' => Types::listOf(Types::getType('ProductElement')),
                ],
                'SECTIONS' => [
                    'type' => Types::listOf(Types::get('ProductSection')),
                ],
            ] + parent::getFieldsInfo();
    }
}
