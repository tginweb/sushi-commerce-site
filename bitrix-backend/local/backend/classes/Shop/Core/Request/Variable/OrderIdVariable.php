<?php

namespace Shop\Core\Request\Variable;

use Main\Request\Variable\RequestVariable;
use Shop\Core\Entity\OrderModel;

class OrderIdVariable extends RequestVariable
{
    function extractValue($value, $arguments)
    {
        $order = OrderModel::query()->getById($value);

        if (!$order) {
            $this->throwInvalid();
        }

        return $order;
    }
}



