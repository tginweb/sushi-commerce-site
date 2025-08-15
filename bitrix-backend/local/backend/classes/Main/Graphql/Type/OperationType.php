<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class OperationType extends ObjectType
{
    const NAME = 'Operation';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'SID' => Types::int(),
            'PROCESSED' => Types::boolean(),
            'NAME' => Types::string(),
            'PROCESSED_REDIRECT' => Types::string(),
        ];
    }

    function resolve_NAME($element, $args, $context)
    {
        return $element->getName();
    }

    function resolve_PROCESSED($element, $args, $context)
    {
        return $element->getProcessed();
    }

    function resolve_PROCESSED_REDIRECT($element, $args, $context)
    {
        return $element->getProcessedRedirect();
    }

}
