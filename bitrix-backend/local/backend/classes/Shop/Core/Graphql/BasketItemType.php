<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use Main\Graphql\Types;

class BasketItemType extends BasketItemBaseType
{
    use Containerable;

    const NAME = 'BasketItem';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'ELEMENT' => [
                    'cache1' => [
                        'ttl' => 1000,
                        'name' => 'basketItemElement',
                        'args' => [
                            'USER_GROUP',
                            'ELEMENT' => function ($ctx) {
                                return $ctx['parent']->getField('PRODUCT_ID');
                            }
                        ]
                    ],
                    'type' => Types::getType('ProductElement'),
                ],
                'BUILD' => [
                    'type' => Types::listOf(Types::get(BasketBuildItemType::class)),
                ],
                'PROPS' => Types::listOf(Types::get(BasketItemPropType::class)),
            ];
    }

    public function resolve_BUILD($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getPropValue('BUILD', []);
    }

    public function resolve_INPUT_PROPS_HASH($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getInputPropsHash();
    }

    public function resolve_PROPS($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getClientProps();
    }

    public function resolve_ELEMENT($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getProduct() ?? $context['dataloader']['element']->load($parent->getField('PRODUCT_ID') . '.list');
    }

    public function resolve_ORDER($parent, $args, $context, ResolveInfo $info)
    {
        $order = $parent->getRelated('ORDER');

        if (!$order) {
            $orderId = $parent->getField('ORDER_ID');
            return $orderId ? $context['dataloader']['order']->load($orderId) : null;
        }

        return $order;
    }
}
