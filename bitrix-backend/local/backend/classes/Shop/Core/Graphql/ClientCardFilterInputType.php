<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityFilterInputType;
use Shop\Core\Entity\ClientCard;

class ClientCardFilterInputType extends HLEntityFilterInputType
{
    const NAME = 'ClientCardFilterInput';

    function getQuery()
    {
        return ClientCard::query();
    }
}
