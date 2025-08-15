<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Adapter;
use Main\Entity\D7\D7Model;

class PersonType extends D7Model
{
    static $itemsAll;
    static $itemsAllById;
    static $adapter;

    use Containerable;

    /**
     * Instantiate adapter if it's not instantiated.
     *
     * @return D7Adapter
     */
    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Internals\PersonTypeTable::class);
    }

    public static function getListAll($refetch = false)
    {
        if ($refetch || !isset(static::$itemsAll)) {
            static::$itemsAll = [];
            static::$itemsAllById = [];

            $personType = new \CSalePersonType();
            $rsPersonTypes = $personType->GetList(['SORT' => 'ASC']);

            while ($arPersonType = $rsPersonTypes->Fetch()) {
                $arPersonType['ID'] = (int)$arPersonType['ID'];
                $arPersonType = new static($arPersonType['ID'], $arPersonType);
                static::$itemsAll[] = $arPersonType;
                static::$itemsAllById[$arPersonType['ID']] = $arPersonType;
            }

            static::$itemsAll = self::container()->applyFilters('sale.person-types', static::$itemsAll);
        }

        return static::$itemsAll;
    }

    public static function getListAllById($refetch = false)
    {
        static::getListAll($refetch);
        return static::$itemsAllById;
    }

    public function isCompany()
    {
        return preg_match('/jur|ip|company/i', $this['CODE']);
    }
}


