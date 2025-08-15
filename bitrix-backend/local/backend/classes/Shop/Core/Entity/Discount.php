<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class Discount extends ElementModel
{
    const PROPS_ALL_PUBLIC = true;

    function getConditions()
    {
        return $this['CONDITIONS'];
    }
}
