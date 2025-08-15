<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Adapter;
use Main\Entity\D7\D7Model;

class Store extends D7Model
{
    static $adapter;
    static $itemsAll;

    /**
     * Instantiate a query object for the model.
     *
     * @return StoreQuery
     */
    public static function query()
    {
        return new StoreQuery(static::instantiateAdapter(), static::class, StoreCollection::class);
    }

    /**
     * Instantiate adapter if it's not instantiated.
     *
     * @return D7Adapter
     */
    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Catalog\StoreTable::class);
    }

    /**
     * @return StoreCollection
     */
    public static function getListAll($refetch = false)
    {
        if ($refetch || !isset(static::$itemsAll))
            static::$itemsAll = static::query()->getList();

        return static::$itemsAll;
    }
}
