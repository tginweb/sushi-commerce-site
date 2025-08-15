<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\CoordinatesType;
use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;
use Shop\Core\Entity\OrderProfile;
use Shop\Core\Entity\PersonType;

class OrderProfileType extends EntityType
{
    const NAME = 'OrderProfile';

    public function getFieldsInfo()
    {
        return [
                'ID' => Types::nonNull(Types::int()),
                'NAME' => Types::nonNull(Types::string()),
                'CAPTION' => Types::string(),
                'USER_ID' => Types::nonNull(Types::int()),
                'USER' => Types::get(UserType::class),
                'PERSON_TYPE_ID' => Types::nonNull(Types::int()),
                'PERSON_TYPE' => Types::get(PersonTypeType::class),
                'COMPANY_ID' => Types::int(),
                'IS_DEFAULT' => Types::boolean(),
                'COORDS' => Types::get(CoordinatesType::class),
                'DELIVERY_FREE_FROM_PRICE' => Types::nonNull(Types::int()),
                'ATTR' => Types::getNonNull(OrderableProfileAttributesValueType::class),
                'ATTRS' => Types::nonNullListOf(Types::getNonNull(OrderAttributeType::class)),
            ] + parent::getFieldsInfo();
    }

    public function resolve_ID(OrderProfile $parent, $args, $context)
    {
        return $parent->id ?: 0;
    }

    public function resolve_DELIVERY_FREE_FROM_PRICE(OrderProfile $parent, $args, $context)
    {
        if (!$this->container->getApp()->isMobileApp()) {
            return 0;
        }

        return $parent->getPropValue('DELIVERY_FREE_FROM_PRICE') ?: 0;
    }

    public function resolve_COORDS(OrderProfile $parent, $args, $context)
    {
        //  die(json_encode($parent->getPropValue('HOUSE_COORDS')));
        return $parent->getCoords();
    }

    public function resolve_IS_DEFAULT(OrderProfile $parent, $args, $context)
    {
        return $parent->isDefault();
    }

    public function resolve_NAME(OrderProfile $parent, $args, $context)
    {
        return $parent->getNameComputed();
    }

    public function resolve_CAPTION(OrderProfile $parent, $args, $context)
    {
        return $parent->getCaptionComputed();
    }

    public function resolve_COMPANY_ID(OrderProfile $parent, $args, $context)
    {
        return $parent->getPropValue('COMPANY_ID') ?: null;
    }

    public function resolve_COMPANY(OrderProfile $parent, $args, $context)
    {
        $id = $parent->getPropValue('COMPANY_ID');
        return $id > 0 ? $context['dataloader']['element']->load($id) : null;
    }

    public function resolve_PERSON_TYPE(OrderProfile $parent, $args, $context)
    {
        $personTypes = PersonType::getListAllById();
        return $personTypes[$parent['PERSON_TYPE_ID']];
    }

    public function resolve_USER(OrderProfile $parent, $args, $context)
    {
        $userId = $parent['USER_ID'];
        return $userId > 0 ? $context['dataloader']['user']->load($userId) : null;
    }

    public function resolve_ATTR(OrderProfile $parent)
    {
        return $parent->getAttributes()->getAttributesValues();
    }

    public function resolve_ATTRS(OrderProfile $parent)
    {
        return $parent->getAttributes();
    }
}
