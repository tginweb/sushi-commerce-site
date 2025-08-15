<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class ReserveAvailableTime extends OrderProperty
{
    public function vorderUpdateFromClientAllow()
    {
        return false;
    }
}


