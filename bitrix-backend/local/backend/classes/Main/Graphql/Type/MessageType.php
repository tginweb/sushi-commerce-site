<?php

namespace Main\Graphql\Type;

use Main\Graphql\Enum\MessageTypeEnum;
use Main\Graphql\Types;
use Menu\Core\Graphql\MenuItemType;

class MessageType extends ObjectType
{
    const NAME = 'Message';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::string(),
            'type' => Types::get(MessageTypeEnum::class),
            'code' => Types::string(),
            'name' => Types::string(),
            'status' => Types::string(),
            'title' => Types::string(),
            'message' => Types::string(),
            'messages' => Types::listOf(Types::string()),
            'category' => Types::string(),
            'notify' => Types::boolean(),
            'rel' => Types::string(),
            'duration' => Types::int(),
            'data' => Types::JSON(),
            'actions' => Types::nonNullListOf(Types::getNonNull(MenuItemType::class)),
        ];
    }
}
