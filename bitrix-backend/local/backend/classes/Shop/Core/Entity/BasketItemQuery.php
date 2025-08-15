<?php

namespace Shop\Core\Entity;

use Bitrix\Sale\BasketBase;
use Main\Entity\D7\OrmQuery;

class BasketItemQuery extends OrmQuery
{
    public static function getClientFiltersInfo()
    {
        return [
        ];
    }

    /**
     * @return BasketBase
     */
    public function getList()
    {
        return $this->loadModels($this->exec()->fetchAll());
    }

    /**
     * @return BasketItem
     */
    public function getFirst()
    {
        $collection = $this->setLimit(1)->loadModels($this->exec()->fetchAll());
        return $collection->current();
    }

    public function loadModels($rows)
    {
        $basket = Basket::createObject();
        $basket->loadFromArray($rows);
        return $basket;
    }
}
