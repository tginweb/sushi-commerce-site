<?php

namespace Shop\Core\Request\Variable;

use Main\Helper\Test;

class UserOrderIdVariable extends OrderIdVariable
{
    function extractValue($value, $arguments)
    {
        $order = parent::extractValue($value, $arguments);
        if (!Test::assertEqualNumbersNotEmpty($this->container->getUserId(), $order->getField('USER_ID'))) {
            $this->throwAccessError();
        }
        return $order;
    }
}



