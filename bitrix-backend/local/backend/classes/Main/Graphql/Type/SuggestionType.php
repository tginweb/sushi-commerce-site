<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class SuggestionType extends ObjectType
{
    const NAME = 'Suggestion';

    function getFieldsInfo()
    {
        $dataType = $this->getSuggestionDataFields();

        if (is_array($dataType)) {
            $dataType = new ObjectType([
                'name' => static::NAME . 'Data',
                'fields' => $dataType
            ]);
        }

        return [
                'label' => Types::string(),
                'value' => Types::string(),
                'data' => $dataType,
            ] + parent::getFieldsInfo();
    }

    function getSuggestionDataFields()
    {
        return [];
    }
}
