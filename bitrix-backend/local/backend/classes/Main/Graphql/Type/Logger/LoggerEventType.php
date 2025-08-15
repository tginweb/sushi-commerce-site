<?php

namespace Main\Graphql\Type\Logger;

use Event\Core\Graphql\EventAppType;
use Main\Graphql\Type\Mongo\DocumentType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;

class LoggerEventType extends DocumentType
{
    const NAME = 'LoggerEvent';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'type' => Types::string(),
                'name' => Types::string(),
                'message' => Types::string(),
                'code' => Types::int(),
                'parent' => Types::string(),

                'data' => Types::JSON(),

                'app' => Types::get(EventAppType::class),
                'sess' => Types::get(LoggerEventSessionType::class),
                'request' => Types::get(LoggerEventRequestType::class),
                'response' => Types::get(LoggerEventResponseType::class),

                'client' => Types::JSON(),

                'created' => Types::JSON(),
                'ended' => Types::JSON(),
                'timer' => Types::int(),

                'user' => Types::get(UserType::class),
            ];
    }

    public function resolve_ACTIONS($parent, $args, $context)
    {
    }

    public function resolve_user($parent, $args, $context)
    {
        // $userId = $parent['sess']['userId'];
        //return $userId > 0 ? $context['dataloader']['user']->load($userId) : null;
    }
}
