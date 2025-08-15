<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class BenefitType extends OrderProperty
{
    public function isEnum()
    {
        return true;
    }

    public function getEnumOptions($value = null, $order = null)
    {
        return $this->container->getBenefitService()->getBenefits();
    }
}


