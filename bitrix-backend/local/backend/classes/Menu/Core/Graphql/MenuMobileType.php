<?php

namespace Menu\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class MenuMobileType extends ObjectType
{
    const NAME = 'MenuMobile';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::string(),
            'code' => Types::string(),
            'children' => Types::listOf(Types::get(MenuItemMobileType::class)),
        ];
    }
}
