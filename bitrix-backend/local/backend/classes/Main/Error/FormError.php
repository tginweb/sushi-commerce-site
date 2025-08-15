<?php

namespace Main\Error;

use Main\Graphql\Types;

class FormError extends BaseError
{
    const TYPE = 'form';

    public static function getNameEnumValues()
    {
        return [
            'field_format_invalid' => [
                'message' => 'Неверный формат поля: ~fieldLabel~',
                'fieldMessage' => 'Неверный формат поля',
            ],
            'field_required' => [
                'message' => 'Обязательное поле: ~fieldLabel~',
                'fieldMessage' => 'Обязательное поле',
            ],
        ];
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'fieldName' => Types::string(),
                'fieldLabel' => Types::string(),
            ] + parent::getGraphQlFieldsInfo();
    }

    function messageGroupBy()
    {
        return ['type', 'name'];
    }
}
