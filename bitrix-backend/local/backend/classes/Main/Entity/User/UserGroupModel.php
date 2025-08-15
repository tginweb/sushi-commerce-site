<?php

namespace Main\Entity\User;

use Main\Entity\Model\BitrixModel;

class UserGroupModel extends BitrixModel
{
    static $itemsAll;
    static $itemsAllIdsByCode;
    static $itemsAllCodesById;

    /**
     * Bitrix entity object.
     *
     * @var object
     */
    public static $bxObject;

    /**
     * Corresponding object class name.
     *
     * @var string
     */
    protected static $objectClass = 'CGroup';

    public static function getIdByCode($code)
    {
        $ids = static::getListAllIdsByCodes();
        return !empty($ids[$code]) ? $ids[$code][0] : null;
    }

    public static function getListAllIdsByCodes($refetch = false)
    {
        if (!isset(static::$itemsAllIdsByCode)) {
            $groups = static::getListAll($refetch);

            $result = [];

            foreach ($groups as $group) {
                $groupCode = !empty($group['STRING_ID']) ? $group['STRING_ID'] : 'EMPTY';
                $result[$groupCode][] = $group['ID'];
            }

            static::$itemsAllIdsByCode = $result;
        }

        return static::$itemsAllIdsByCode;
    }

    /**
     * @return UserGroupCollection
     */
    public static function getListAll($refetch = false)
    {
        if ($refetch || !isset(static::$itemsAll))
            static::$itemsAll = static::query()->getList();

        return static::$itemsAll;
    }

    /**
     * Instantiate a query object for the model.
     *
     * @return UserGroupQuery
     */
    public static function query()
    {
        return new UserGroupQuery(static::instantiateObject(), get_called_class());
    }

    public static function getListAllCodesById($refetch = false)
    {
        if (!isset(static::$itemsAllCodesById)) {
            $groups = static::getListAll($refetch);

            $result = [];

            foreach ($groups as $group) {
                if ($group['STRING_ID'])
                    $result[$group['ID']][] = $group['STRING_ID'];
            }

            static::$itemsAllCodesById = $result;
        }

        return static::$itemsAllCodesById;
    }
}
