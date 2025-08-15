<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class VorderSummaryType extends ObjectType
{
    const NAME = 'VorderSummary';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'PHONE' => Types::string(),
                'EMAIL' => Types::string(),
                'FUSER_ID' => Types::int(),
            ];
    }
}
