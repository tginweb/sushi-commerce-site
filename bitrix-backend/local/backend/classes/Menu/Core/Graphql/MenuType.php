<?php

namespace Menu\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class MenuType extends ObjectType
{
    const NAME = 'Menu';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::string(),
            'code' => Types::string(),
            'children' => Types::listOf(Types::get(MenuItemType::class)),
        ];
    }
}
