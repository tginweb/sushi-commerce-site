<?php

namespace Main\Graphql\Type\Filter;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class DateFilterInput extends InputType
{
    const NAME = 'DateFilterInput';

    public function getFieldsInfo()
    {
        return [
            'eq' => [
                'type' => Types::string(),
            ],
            'in' => [
                'type' => Types::listOf(Types::string()),
            ],
            'not' => [
                'type' => Types::string(),
            ],
            'gt' => [
                'type' => Types::string(),
            ],
            'lt' => [
                'type' => Types::string(),
            ],
        ];
    }

    public function make($query, $field, $value)
    {
        if (isset($value['in'])) {
            $query[$field] = $value['in'];
        }
        if (isset($value['nin'])) {
            $query['!' . $field] = $value['in'];
        }
        return $query;
    }
}
