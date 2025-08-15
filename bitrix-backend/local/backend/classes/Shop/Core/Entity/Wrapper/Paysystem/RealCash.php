<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

class RealCash extends BaseReal
{
    function getPayNav($payment)
    {
        $data = [
            'type' => 'location',
            'path' => $this->getPaymentLink($payment)
        ];
        return $data;
    }
}


