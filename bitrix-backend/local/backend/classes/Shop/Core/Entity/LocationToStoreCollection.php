<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class LocationToStoreCollection extends ModelCollection
{
    function filterByAmount($amountStores)
    {
        $resultStoreIds = [];

        foreach ($amountStores as $amountStore) {
            if ($amountStore['REAL_AMOUNT'] > 0) {
                $resultStoreIds[] = $amountStore['ID'];
            }
        }

        return $this->filter(function ($item) use ($resultStoreIds) {
            return in_array($item['STORE']['ID'], $resultStoreIds);
        });
    }
}


