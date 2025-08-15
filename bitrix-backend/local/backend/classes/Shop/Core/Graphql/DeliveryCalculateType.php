<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Shop\Core\Entity\DeliveryCalculate;

class DeliveryCalculateType extends HLEntityType
{
    const NAME = 'DeliveryCalculate';

    public static function getModelClass()
    {
        return DeliveryCalculate::class;
    }
}
