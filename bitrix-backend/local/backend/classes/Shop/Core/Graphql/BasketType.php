<?php

namespace Shop\Core\Graphql;

use Main\DI\Containerable;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Entity\Basket;

class BasketType extends ObjectType
{
    use Containerable;

    const NAME = 'Basket';

    public function getFieldsInfo()
    {
        return [
            'TS' => Types::string(),
            'SYNCED' => Types::boolean(),
            'BASE_PRICE' => Types::float(),
            'PRICE' => Types::float(),
            'COUNT' => Types::int(),
            'QUANTITY' => Types::float(),
            'ITEMS' => Types::nonNullListOf(Types::getNonNull(BasketItemType::class)),
            'WEIGHT' => Types::float(),
            //'VORDER' => Types::get(VorderType::class),

            'MIN_PRICE' => Types::float(),
            'MIN_PRICE_REACHED' => Types::boolean(),

            'HASH' => Types::string(),

            'OFFERS' => Types::listOf(Types::get(SpecialOfferType::class)),
            'GIFTS' => Types::nonNullListOf(Types::int()),

            'CLIENT_CHANGED_AT' => Types::nonNull(Types::int()),
        ];
    }

    public function resolve_CLIENT_CHANGED_AT($parent, $args, $ctx)
    {
        return time() * 1000;
    }

    public function resolve_GIFTS($parent, $args, $ctx)
    {
        if ($parent->getBasketItemsCollectionInstance()->getQuantity() > 2) {
            return [874200];
        } else {
            return [];
        }
    }

    public function resolve_TS($parent, $args, $ctx)
    {
        return date('H:i:s');
    }

    public function resolve_SYNCED($parent, $args, $ctx)
    {
        return true;
    }

    public function resolve_HASH($parent, $args, $ctx)
    {
        return $parent->getHash();
    }

    public function resolve_OFFERS($parent, $args, $ctx)
    {
        return $parent->getClientOffers();
    }

    public function resolve_MIN_PRICE($parent, $args, $ctx)
    {
        return $parent->getMinPrice();
    }

    public function resolve_MIN_PRICE_REACHED($parent, $args, $ctx)
    {
        return $parent->isMinPriceReached();
    }

    public function resolve_VORDER($parent, $args, $ctx)
    {
        return $this->container->getVorder();
    }

    public function resolve_BASE_PRICE($parent, $args, $ctx)
    {
        return $parent->getBasePriceWithoutGifts();
    }

    public function resolve_PRICE($parent, $args, $ctx)
    {
        return $parent->getPrice();
    }

    public function resolve_QUANTITY($parent, $args, $ctx)
    {
        return $parent->getBasketItemsCollectionInstance()->getQuantity();
    }

    public function resolve_WEIGHT($parent, $args, $ctx)
    {
        return round($parent->getBasketItemsCollectionInstance()->getQuantity(), 2);
    }

    public function resolve_COUNT($parent, $args, $ctx)
    {
        return $parent->getBasketItemsCollectionInstance()->count();
    }

    public function resolve_ITEMS(Basket $parent, $args, $ctx)
    {
        return $parent->getBasketItemsCollectionInstance()->getClientItems();
    }
}
