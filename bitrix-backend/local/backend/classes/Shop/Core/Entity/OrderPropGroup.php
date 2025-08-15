<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Adapter;
use Main\Entity\D7\D7Model;
use Main\Entity\Model\ModelCollection;

class OrderPropGroup extends D7Model
{
    use Containerable;

    static $adapter;
    static $itemsAll;

    public $params;
    public $role;

    /**
     * Instantiate a query object for the model.
     *
     * @return OrderPropGroupQuery
     */
    public static function query()
    {
        return new OrderPropGroupQuery(static::instantiateAdapter(), static::class, ModelCollection::class);
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

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Internals\OrderPropsGroupTable::class);
    }

    /**
     * @return OrderPropertyCollection
     */
    public static function getListAll($refetch = false)
    {
        if ($refetch || !isset(static::$itemsAll))
            static::$itemsAll = static::query()->getList();
        return static::$itemsAll;
    }

    /**
     * @return static
     */
    public static function getByIdCached($id)
    {
        static::getListAll();
        return static::$itemsAll[$id] ?? null;
    }
}


