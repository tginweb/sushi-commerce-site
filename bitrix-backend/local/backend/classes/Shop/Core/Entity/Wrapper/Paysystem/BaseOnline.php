<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

class BaseOnline extends Base
{
    function getDefaultOnlinePaymentWhen() {
        return 'now';
    }

    function isOnline() {
        return true;
    }
}


