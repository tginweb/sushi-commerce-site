<?php

namespace Event\Core\Graphql;

use Event\Core\Entity\ClientNotice;
use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use Menu\Core\Graphql\MenuItemType;
use TG\Main\Helper;

class ClientNoticeType extends HLEntityType
{
    const NAME = 'ClientNotice';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::nonNull(Types::int()),
            'title' => Types::string(),
            'message' => Types::string(),
            'body' => Types::string(),
            'bodyHtml' => Types::string(),
            'image' => Types::string(),
            'haveBody' => Types::boolean(),
            'showAs' => Types::string(),
            'offerId' => Types::int(),

            'eventName' => Types::string(),
            'eventData' => Types::JSON(),
            'eventGroup' => Types::string(),

            'targetUserId' => Types::int(),
            'targetClientId' => Types::string(),

            'targetCode' => Types::string(),
            'createdAt' => Types::date(true),

            'actionItems' => Types::nonNullListOf(Types::getNonNull(MenuItemType::class)),

            'cls' => Types::string(),
            'isReaded' => Types::boolean(),
        ];
    }

    public function resolve_isReaded(ClientNotice $element, $args, $context)
    {
        return $element->getIsReaded();
    }

    public function resolve_id(ClientNotice $element, $args, $context)
    {
        return $element->id;
    }

    public function resolve_offerId(ClientNotice $element, $args, $context)
    {
        return $element->getOfferId();
    }

    public function resolve_haveBody(ClientNotice $element, $args, $context)
    {
        return $element->getHaveBody();
    }

    public function resolve_showAs(ClientNotice $element, $args, $context)
    {
        return $element->getShowAs();
    }

    public function resolve_image(ClientNotice $element, $args, $context)
    {
        return $element->getImage();
    }

    public function resolve_targetCode(ClientNotice $element, $args, $context)
    {
        return $element->getTargetCode();
    }

    public function resolve_cls(ClientNotice $element, $args, $context)
    {
        return get_class($element);
    }

    public function resolve_title(ClientNotice $element, $args, $context)
    {
        return $element->getTitle();
    }

    public function resolve_message(ClientNotice $element, $args, $context)
    {
        return $element->getMessage();
    }

    public function resolve_body(ClientNotice $element, $args, $context)
    {
        return $element->getBody();
    }

    public function resolve_bodyHtml(ClientNotice $element, $args, $context)
    {
        return $element->getBodyHtml();
    }

    public function resolve_createdAt(ClientNotice $element, $args, $context)
    {
        return DateTime::parseAndFormat($element->createdAt, $args['format']);
    }

    public function resolve_target(ClientNotice $element, $args, $context)
    {
        return !empty($element->target) ? $context['dataloader']['uf_enum']->load($element->target) : null;
    }

    public function resolve_eventData(ClientNotice $element, $args, $context)
    {
        return $element->getEventData();
    }

    public function resolve_actionItems(ClientNotice $element, $args, $context)
    {
        return $element->getActions('mobile');
    }
}
