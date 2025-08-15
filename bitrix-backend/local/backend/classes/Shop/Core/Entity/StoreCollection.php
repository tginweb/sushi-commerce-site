<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class StoreCollection extends ModelCollection
{
    function getByLocationCode($locationCode)
    {
        return $this->filter(function ($item) use ($locationCode) {
            if (is_array($item['UF_LOCATIONS'])) {
                return in_array($locationCode, $item['UF_LOCATIONS']);
            }
        });
    }
}


