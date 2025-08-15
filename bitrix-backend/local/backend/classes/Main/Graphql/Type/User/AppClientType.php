<?php

namespace Main\Graphql\Type\User;

use Main\Entity\User\AppClientModel;
use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;

class AppClientType extends HLEntityType
{
    const NAME = 'AppClient';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'CLIENT_ID' => Types::string(),
                'TOKEN' => Types::string(),
                'SESSION_ID' => Types::string(),
                'CURRENT_SESSION_ID' => Types::string(),
                'USER_ID' => Types::int(),
                'WEB_PUSH_TOKEN' => Types::string(),
                'MOBILE_PUSH_TOKEN' => Types::string(),
                'DEBUG_PARAMS' => Types::get(AppClientDebugParamsType::class),
            ];
    }

    public function resolve_MOBILE_PUSH_TOKEN(AppClientModel $element, $args, $context)
    {
        return $element->getPushToken();
    }

    public function resolve_WEB_PUSH_TOKEN(AppClientModel $element, $args, $context)
    {
        return $element->getWebPushToken();
    }

    public function resolve_CURRENT_SESSION_ID(AppClientModel $element, $args, $context)
    {
        return session_id();
    }

    public function resolve_DEBUG_PARAMS(AppClientModel $element, $args, $context)
    {
        return $element->getDebugParams();
    }
}
