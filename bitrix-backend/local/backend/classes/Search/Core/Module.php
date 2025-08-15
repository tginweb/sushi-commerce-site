<?php

namespace Search\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = array())
    {
        $container = $this->container;
        $container->define(Service::class);
    }

    function registerTypes()
    {
        Types::types([
            'SearchSuggestionFilter' => \Search\Core\Graphql\SearchSuggestionFilter::class,
            'SearchSuggestion' => \Search\Core\Graphql\SearchSuggestionType::class,
            'SearchSuggestionData' => \Search\Core\Graphql\SearchSuggestionDataType::class,
        ]);
    }
}
