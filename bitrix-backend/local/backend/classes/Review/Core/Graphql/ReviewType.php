<?php

namespace Review\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\IBlock\ElementModel;
use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;
use Review\Core\Entity\Review;
use Shop\Core\Graphql\OrderType;

class ReviewType extends ElementType
{
    const NAME = 'Review';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ELEMENT' => Types::get(ElementType::class),
                'CHILDREN' => Types::listOf(Types::get(ReviewType::class)),
                'ORDER' => Types::get(OrderType::class),
            ];
    }

    static function iblockId()
    {
        return Review::getIblockIdOrThrow();
    }

    static function getAllowedProps()
    {
        return ['RATING', 'AUTHOR_NAME', 'ANSWER'];
    }

    public function resolve_ORDER($element, $args, $context)
    {
        $orderId = $element->getProp('CONTEXT_ID');
        return $orderId > 0 ? $context['dataloader']['order']->load($orderId) : null;
    }

    public function resolve_USER($element, $args, $context)
    {
        $userId = $element['PROPERTY_AUTHOR_USER_ID_VALUE'];
        return $userId > 0 ? $context['dataloader']['user']->load($userId) : null;
    }

    public function resolve_ELEMENT($element, $args, $context, ResolveInfo $info)
    {
        $id = $element['PROPERTY_ELEMENT_ID_VALUE'];
        return $id ? $context['dataloader']['element']->load($id) : null;
    }

    public function resolve_CHILDREN($element, $args, $context, ResolveInfo $info)
    {
        $contextId = $element['PROPERTY_CONTEXT_ID_VALUE'];

        $target = $element->getProp('TARGET', 'XML_ID', false);

        if ($target === 'order') {
            return ElementModel::query()
                ->filter([
                    'PROPERTY_TARGET_XML_ID' => 'product',
                    'PROPERTY_CONTEXT_ID' => $contextId,
                ])
                ->withViewList()
                ->getList();
        }

        return [];
    }
}
