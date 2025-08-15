<?php

namespace Shop\Core\Entity;

use Bitrix\Sale\Location\Admin\LocationHelper;
use Main\Entity\D7\D7Adapter;
use Main\Entity\D7\D7Model;

class Location extends D7Model
{
    static $adapter;
    static $itemsAll;
    static $itemsDefaultAll;

    public static function query()
    {
        return new LocationQuery(static::class, LocationCollection::class);
    }

    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Location\LocationTable::class);
    }

    /**
     * @return LocationCollection
     */
    public static function getListAll($refetch = false)
    {
        if ($refetch || !isset(static::$itemsAll))
            static::$itemsAll = static::query()->getList();

        return static::$itemsAll;
    }


    public function getZip()
    {
        if (!isset($this["ZIP"])) {
            $extLoc = LocationHelper::getZipByLocation($this['CODE'], array('limit' => 1))->fetch();
            if (!empty($extLoc['XML_ID']))
                $this["ZIP"] = $extLoc['XML_ID'];
            else
                $this["ZIP"] = false;
        }
        return $this["ZIP"];
    }

    public function getLocationToStoreList()
    {
        $result = [];

        foreach ($this->getCatalogOutZoneStores() as $store) {
            $item = new LocationToStore();
            $item['DELIVERY_TIME_DAYS'] = intval($store['UF_DELIVERY_OUTZONE_TIME']);
            $item['STORE'] = $store;
            $result[] = $item;
        }

        foreach ($this->getCatalogInZoneStores() as $store) {
            $item = new LocationToStore();
            $item['DELIVERY_TIME_DAYS'] = 0;
            $item['STORE'] = $store;
            $result[] = $item;
        }

        return new LocationToStoreCollection($result);
    }

    public function getCatalogStoresIds()
    {
        return $this->getCatalogStores()->pluckArray('ID', 'ID');
    }

    public function getCatalogStores()
    {

        return Store::getListAll()->filter(function ($item) {
            if (!empty($item['UF_DELIVERY_OUTZONE_LOCATIONS']) && in_array($this['CODE'], $item['UF_DELIVERY_OUTZONE_LOCATIONS'])) {
                return true;
            }
            if (is_array($item['UF_DELIVERY_INZONE_LOCATIONS']) && in_array($this['CODE'], $item['UF_DELIVERY_INZONE_LOCATIONS'])) {
                return true;
            }
        });
    }

    public function getCatalogOutZoneStores()
    {
        return Store::getListAll()->filter(function ($item) {
            if (is_array($item['UF_DELIVERY_OUTZONE_LOCATIONS'])) {
                return in_array($this['CODE'], $item['UF_DELIVERY_OUTZONE_LOCATIONS']);
            }
        });
    }

    public function getCatalogInZoneStores()
    {
        return Store::getListAll()->filter(function ($item) {
            if (is_array($item['UF_DELIVERY_INZONE_LOCATIONS'])) {
                return in_array($this['CODE'], $item['UF_DELIVERY_INZONE_LOCATIONS']);
            }
        });
    }

    public function getNearestLocationToStoreByAmount($amountStores)
    {
        return $this
            ->getLocationToStoreList()
            ->filterByAmount($amountStores)
            ->sortBy('DELIVERY_TIME_DAYS', SORT_ASC)
            ->first();
    }

    public function getRegion()
    {
        $parent = static::query()->getById($this['PARENT_ID']);
        return $parent;
    }
}


