<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

class Bill extends Base
{
    function getPayNav($payment)
    {
        $data = [
            'type' => 'location',
            'path' => $this->getPaymentLink($payment)
        ];
        return $data;
    }

    function isBill()
    {
        return true;
    }
}


