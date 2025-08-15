<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class DiscountCollection extends ModelCollection
{
    function filterGuest()
    {
        return $this;
    }

    function filterByClientCard(ClientCard $clientCard = null)
    {
        return $this->filter(function ($item) use ($clientCard) {
            return true;
        });
    }

    function filterActual(OrderModel $order)
    {
        return $this->filter(function ($item) use ($order) {

            foreach ($item['CONDITIONS'] as $condition) {
                switch ($condition['TYPE']) {
                    case 'transport_type':
                        switch ($condition['VALUE']) {
                            case 'pickup':
                                if (!$order->isSelfPickup()) return false;
                                break;
                            case 'courier':
                                if ($order->isSelfPickup()) return false;
                                break;
                        }
                        break;
                }
            }

            return true;
        });
    }

    function getMaximal()
    {
        $sorted = $this->sort(function ($a, $b) {
            return $a['PERCENT'] < $b['PERCENT'];
        });

        return $sorted->first();
    }
}
