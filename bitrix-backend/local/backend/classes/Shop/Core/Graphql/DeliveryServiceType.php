<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Enum\DeliveryTransportTypeEnum;
use TG\Shop\Core\Entity\Wrapper;

class DeliveryServiceType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'DeliveryService',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'PARENT_ID' => Types::int(),
            'TRANSPORT_TYPE' => Types::get(DeliveryTransportTypeEnum::class),
        ];
    }

    function getWrapper($parent)
    {
        return \Shop\Core\Entity\Wrapper\Delivery\Base::wrap($parent) ?: null;
    }

    public function resolve_PARENT_ID($parent, $args, $ctx)
    {
        //return $this->getParentId();
    }

    public function resolve_TRANSPORT_TYPE($parent, $args, $ctx)
    {
        return $this->getWrapper($parent)->getTransportType();
    }
}
