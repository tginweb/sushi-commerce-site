<?php

namespace Main\Graphql\Type\Filter;

use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class IntFilterInput extends InputType
{
    const NAME = 'IntFilterInput';

    public function getFieldsInfo()
    {
        return [
            'exists' => [
                'type' => Types::boolean(),
            ],
            'eq' => [
                'type' => Types::int(),
            ],
            'in' => [
                'type' => Types::listOf(Types::int()),
            ],
            'not' => [
                'type' => Types::int(),
            ],
            'gt' => [
                'type' => Types::int()
            ],
            'lt' => [
                'type' => Types::int()
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
