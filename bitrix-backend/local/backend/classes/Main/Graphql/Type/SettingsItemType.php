<?php

namespace Main\Graphql\Type;

use Main\Graphql\Types;

class SettingsItemType extends ObjectType
{
    const NAME = 'SettingsItem';

    public function getFieldsInfo()
    {
        return [
            'key' => Types::string(),
            'value' => Types::JSON(),
        ];
    }
}
