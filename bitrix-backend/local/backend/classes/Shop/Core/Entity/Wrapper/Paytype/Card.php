<?php

namespace Shop\Core\Entity\Wrapper\Paytype;

class Card extends Base
{
    function isTerminal()
    {
        return true;
    }
}


