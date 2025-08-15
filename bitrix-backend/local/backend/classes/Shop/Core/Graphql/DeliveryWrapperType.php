<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Enum\DeliveryTransportTypeEnum;

class DeliveryWrapperType extends ObjectType
{
    const NAME = 'DeliveryWrapper';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'TRANSPORT_TYPE' => Types::get(DeliveryTransportTypeEnum::class),
            'HOST' => Types::get(DeliveryServiceType::class),
        ];
    }

    public function resolve_ID($parent, $args, $ctx)
    {
        return $parent->id;
    }

    public function resolve_NAME($parent, $args, $ctx)
    {
        return $parent->host['NAME'];
    }

    public function resolve_TRANSPORT_TYPE($parent, $args, $ctx)
    {
        return $parent->getTransportType();
    }
}
