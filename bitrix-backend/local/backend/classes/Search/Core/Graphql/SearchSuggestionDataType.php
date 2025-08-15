<?php

namespace Search\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SearchSuggestionDataType extends ObjectType
{
    const NAME = 'SearchSuggestionData';

    function getFieldsInfo()
    {
        return [
            'type' => Types::string(),
            'entityTypeName' => Types::string(),
            'entityTypeCode' => Types::string(),
            'entityTypeId' => Types::string(),
            'entityRole' => Types::string(),
            'entityId' => Types::int(),
            'entityTitle' => Types::string(),
            'hint' => Types::string(),
        ];
    }
}

