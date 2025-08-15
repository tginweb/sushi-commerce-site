<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class GiftCertificate extends ElementModel
{
    function onBeforeCreate()
    {
        $this->setPropValue('CODE', $this->genereateCode());
    }

    function genereateCode()
    {
        return 'G-' . strtoupper(randString(6));
    }
}
