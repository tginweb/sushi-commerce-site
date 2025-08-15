<?php

namespace Main\Graphql\Type\Filter;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class StringFilterInput extends InputType
{
    const NAME = 'StringFilterInput';

    public function getFieldsInfo()
    {
        return [
            'exists' => [
                'type' => Types::boolean(),
            ],
            'eq' => [
                'type' => Types::string(),
            ],
            'in' => [
                'type' => Types::listOf(Types::string()),
            ],
            'not' => [
                'type' => Types::string(),
            ],
            'like' => [
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
