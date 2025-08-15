<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class Department extends OrderProperty
{
    public function getEnumOptions($value = null, $order = null)
    {
        return [];
    }
}


