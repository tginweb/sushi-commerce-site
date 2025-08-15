<?php

namespace Shop\Core\Entity\Wrapper\Delivery;

class Pickup extends Base
{
    function getTransportType()
    {
        return 'pickup';
    }
}


