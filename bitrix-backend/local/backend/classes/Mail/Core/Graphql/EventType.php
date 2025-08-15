<?php

namespace Mail\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use TG\Main\Helper;

class EventType extends EntityType
{
    const NAME = 'MailEvent';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ID' => Types::int(),
                'EVENT_NAME' => Types::string(),
                'MESSAGE_ID' => Types::int(),
                'FIELDS' => Types::JSON(),
                'DATE_INSERT' => Types::date(),
                'DATE_EXEC' => Types::date(),
                'SUCCESS_EXEC' => Types::boolean(),
                'EVENT' => Types::get(EventTypeType::class),

                'ORDER_ID' => Types::int(),
                'ORDER' => Types::getType('Order'),

                'USER_ID' => Types::int(),
                'USER' => Types::getType('User'),
            ];
    }

    public function resolve_ORDER_ID($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getOrderId();
    }

    public function resolve_USER_ID($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getUserId();
    }

    public function resolve_USER($parent, $args, $context, ResolveInfo $info)
    {
        $userId = $parent->getUserId();
        return $userId ? $context['dataloader']['user']->load($userId) : null;
    }

    public function resolve_ORDER($parent, $args, $context, ResolveInfo $info)
    {
        $orderId = $parent->getOrderId();
        return $orderId ? $context['dataloader']['order']->load($orderId) : null;
    }

    public function resolve_EVENT($parent, $args, $ctx)
    {
        $eventName = $parent['EVENT_NAME'];
        return $ctx['dataloader']['mailEventType']->load($eventName);
    }

    public function resolve_FIELDS($parent, $args, $ctx)
    {
        return $parent['C_FIELDS'];
    }

    public function resolve_DATE_INSERT($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent['DATE_INSERT'], $args['format']);
    }

    public function resolve_DATE_EXEC($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent['DATE_EXEC'], $args['format']);
    }

    public function resolve_SUCCESS_EXEC($parent, $args, $ctx)
    {
        return $parent['SUCCESS_EXEC'] === 'Y';
    }
}
