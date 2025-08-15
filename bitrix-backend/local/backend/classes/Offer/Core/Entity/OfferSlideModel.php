<?php

namespace Offer\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class OfferSlideModel extends ElementModel
{
    const ENTITY_TYPE = 'offer_slide';

    public $contextVars = [];

    public function setContextVars($vars = [])
    {
        $this->contextVars += $vars;
        return $this;
    }
}
