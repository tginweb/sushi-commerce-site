<?php

namespace Search\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SearchSuggestionType extends ObjectType
{
    const NAME = 'SearchSuggestion';

    public function getFieldsInfo()
    {
        return [
            'label' => Types::string(),
            'value' => Types::string(),
            'data' => Types::get(SearchSuggestionDataType::class)
        ];
    }
}

