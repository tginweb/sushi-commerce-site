<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;
use function TG\Shop\Core\Entity\OrderProperty\FormatDate;

class Date extends OrderProperty
{
    function getEnumOptions($value = null, $order = null)
    {
        return [];
    }
}


