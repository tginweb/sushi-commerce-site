<?php

namespace Main\Error;

class CommonError extends BaseError
{
    const TYPE = 'common';

    public static function getNameEnumValues()
    {
        return [
            'common' => ['message' => 'Ошибка'],
            'common_not_found' => ['message' => 'Не найдено'],
            'common_input_error' => ['message' => 'Ошибка ввода'],
            'common_invalid_format' => ['message' => 'Неверный формат'],
        ];
    }
}
