<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

class OnlinePaykeeper extends BaseOnline
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


