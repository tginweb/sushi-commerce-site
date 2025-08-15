<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityFilterInputType;
use Shop\Core\Entity\DeliveryCalculate;

class DeliveryCalculateFilterInputType extends HLEntityFilterInputType
{
    const NAME = 'DeliveryCalculateFilterInput';

    function getQuery()
    {
        return DeliveryCalculate::query();
    }
}
