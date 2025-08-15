<?php

namespace Shop\Core\Entity\OrderProperty;

use Main\Graphql\Types;
use Shop\Core\Entity\OrderProperty;

class Json extends OrderProperty
{
    public function getGraphqlType()
    {
        return Types::JSON();
    }
}


