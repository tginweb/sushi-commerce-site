<?php

namespace Search\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementFilterInputType;

class SearchSuggestionFilter extends ElementFilterInputType
{
    const NAME = 'SearchSuggestionFilter';

    public function __construct()
    {
        parent::__construct([
            'name' => static::NAME,
            'description' => '',
            'fields' => [$this, 'getFieldsInfo']
        ]);
    }
}
