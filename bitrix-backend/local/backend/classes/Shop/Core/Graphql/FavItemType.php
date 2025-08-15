<?php

namespace Shop\Core\Graphql;

use Main\DI\Containerable;

class FavItemType extends BasketItemType
{
    use Containerable;

    const NAME = 'FavItem';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo();
    }
}
