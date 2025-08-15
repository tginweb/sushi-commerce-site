<?php

namespace Main\Graphql\Type\User;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Menu\Core\Graphql\MenuItemMobileType;
use Menu\Core\Graphql\MenuItemType;

class UserAuthConfirmType extends ObjectType
{
    const NAME = 'UserAuthConfirm';

    public function getFieldsInfo()
    {
        return [
            'NAME' => Types::nonNull(Types::string()),
            'CODE' => Types::nonNull(Types::string()),
            'COLOR' => Types::string(),
            'ICON' => Types::string(),

            'LIST_NAME' => Types::string(),
            'LIST_CAPTION' => Types::string(),

            'LIST_BUTTON_MOBILE' => Types::get(MenuItemMobileType::class),
            'LIST_BUTTON_WEB' => Types::get(MenuItemType::class),

            'CONFIRM_CONTENT_MOBILE' => Types::string(),
            'CONFIRM_CONTENT_WEB' => Types::string(),

            'CONFIRM_STEPS' => Types::listOf(Types::get(UserAuthConfirmStepType::class)),

            'RESEND_TITLE' => Types::string()
        ];
    }
}
