<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Bitrix\Sale\BasketBase;
use Main\DI\Containerable;

class FavBasket extends Sale\Basket
{
    use Containerable;

    public $extCollectionInstance = null;

    public static function create($siteId)
    {
        $basket = new static;
        $basket->setSiteId($siteId);

        return $basket;
    }

    public static function getList(array $parameters = array())
    {
        $parameters['filter']['DELAY'] = 'Y';
        return parent::getList($parameters);
    }

    /**
     * @return BasketBase
     */
    public function getBasket()
    {
        return $this;
    }

    /**
     * @return BasketItemCollection
     */
    public function getBasketItemsCollectionInstance($reload = false)
    {
        if (!isset($this->extCollectionInstance) || $reload) {
            $this->extCollectionInstance = $this->getBasketItemsCollection();
        }
        return $this->extCollectionInstance;
    }

    public function itemsLoadAll($refetch = false)
    {
        return $this->getBasketItemsCollectionInstance($refetch)->withProducts($refetch)->withProps($refetch);
    }

    public function getBasketItemsCollection()
    {
        return new BasketItemCollection($this->collection, $this->container->getCatalogProductElementModelClass());
    }
}
