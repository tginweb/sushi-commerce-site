<?php

namespace Event\Core\Graphql;

use Event\Core\Entity\ClientEmit;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use TG\Main\Helper;

class ClientEmitType extends ObjectType
{
    const NAME = 'ClientEmit';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::int(),
            'title' => Types::string(),
            'message' => Types::string(),
            'body' => Types::string(),

            'eventName' => Types::string(),
            'eventData' => Types::JSON(),
            'eventGroup' => Types::string(),

            'targetUserId' => Types::int(),
            'targetClientId' => Types::string(),

            'createdAt' => Types::date(true),

            'cls' => Types::string(),
        ];
    }

    public function resolve_id(ClientEmit $element, $args, $context)
    {
        return $element->id;
    }

    public function resolve_cls(ClientEmit $element, $args, $context)
    {
        return get_class($element);
    }

    public function resolve_title(ClientEmit $parent, $args, $context)
    {
        return $parent->getTitle();
    }

    public function resolve_message(ClientEmit $parent, $args, $context)
    {
        return $parent->getMessage();
    }

    public function resolve_body(ClientEmit $parent, $args, $context)
    {
        return $parent->getBody();
    }

    public function resolve_createdAt(ClientEmit $parent, $args, $context)
    {
        return DateTime::parseAndFormat($parent->createdAt, $args['format']);
    }

    public function resolve_eventData(ClientEmit $parent, $args, $context)
    {
        return $parent->getEventData();
    }
}
