<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class DeliveryZone extends ElementModel
{
    const PROPS_ALL_PUBLIC = true;

    public function getUrl()
    {
        return '/contact/' . $this['CODE'];
    }
}
