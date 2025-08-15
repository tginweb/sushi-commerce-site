<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class BasketItemsStatType extends ObjectType
{
    use Containerable;

    const NAME = 'BasketItemStat';

    public function getFieldsInfo()
    {
        return [
            'SECTIONS' => Types::int(),
            'PRICE' => Types::int(),
        ];
    }

    public function resolveField($parent, $args, $context, ResolveInfo $info)
    {
        $method = 'resolve_' . ucfirst($info->fieldName);
        if (method_exists($this, $method)) {
            return $this->{$method}($parent, $args, $context, $info);
        } else {
            return $parent->getField($info->fieldName);
        }
    }

    public function resolve_COMMENT($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getProp('COMMENT');
    }

    public function resolve_BASE_PRICE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getField('BASE_PRICE');
    }

    public function resolve_PRICE_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getField('BASE_PRICE');
    }

    public function resolve_FINAL_PRICE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getFinalPrice();
    }


    public function resolve_FINAL_PRICE_BASE($parent, $args, $context, ResolveInfo $info)
    {
        return $parent->getFinalPriceBase();
    }

    public function resolve_ELEMENT($parent, $args, $context, ResolveInfo $info)
    {
        return;
        return $context['dataloader']['element']->load($this->container->getCatalogProductElementModelClass() . ':' . $parent->getField('PRODUCT_ID'));
    }
}
