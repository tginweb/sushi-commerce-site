<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;
use Shop\Core\Entity\ClientCard;

class ClientCardType extends HLEntityType
{
    const NAME = 'SaleClientCard';

    public static function getModelClass()
    {
        return ClientCard::class;
    }

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'LEVEL' => Types::get(BonusLevelType::class),
                'DISCOUNTS' => Types::nonNullListOf(Types::getNonNull(DiscountType::class)),
                'FETCHED_ACTUAL' => Types::boolean(),
                'EXPIRED' => Types::boolean(),
            ];
    }

    public function resolve_EXPIRED($parent, $args, $ctx)
    {
        return $parent->isExpired();
    }

    public function resolve_FETCHED_ACTUAL($parent, $args, $ctx)
    {
        return $parent->isFetchedActual();
    }

    public function resolve_DISCOUNTS($parent, $args, $ctx)
    {
        return $parent->getDiscounts();
    }

    public function resolve_LEVEL($parent, $args, $ctx)
    {
        return $parent->getLevelModel();
    }
}
