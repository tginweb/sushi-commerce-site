<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class PaymentType extends OrderProperty
{
    public function isEnum()
    {
        return true;
    }

    public function getEnumOptions($value = null, $order = null)
    {
        return $this->container->getPaymentService()->getPaymentTypes()->all();
    }
}


