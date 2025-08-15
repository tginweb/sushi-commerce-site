<?php

namespace Main\Helper;

use Bitrix\Main\Application;
use Exception;
use RuntimeException;
use TG\Main\Cacher\Lib\Cache;

class IblockId
{
    use Cacheable;

    /**
     * Получение ID инфоблока по коду (или по коду и типу).
     * Помогает вовремя обнаруживать опечатки.
     *
     * @param string $code
     * @param string|null $type
     * @return int
     *
     * @throws RuntimeException
     * @throws Exception
     */
    public static function getByCode($code, $type = null)
    {
        if (is_null(static::$values)) {
            static::$values = static::getAllByCodes();
        }

        if (!is_null($type)) {
            $code = $type . ':' . $code;
        }

        if (!isset(static::$values[$code])) {
            throw new RuntimeException("Iblock with code '{$code}' was not found");
        }

        return static::$values[$code];
    }

    /**
     * Получение ID всех инфоблоков из БД/кэша.
     *
     * @return array
     * @throws Exception
     */
    public static function getAllByCodes()
    {
        $callback = function () {
            $iblocks = [];

            $sql = 'SELECT ID, CODE, IBLOCK_TYPE_ID FROM b_iblock WHERE CODE != ""';
            $dbRes = Application::getConnection()->query($sql);
            while ($i = $dbRes->fetch()) {
                $id = (int)$i['ID'];
                $iblocks[$i['CODE']] = $id;
                $iblocks[$i['IBLOCK_TYPE_ID'] . ':' . $i['CODE']] = $id;
            }

            return $iblocks;
        };

        return static::$cacheMinutes
            ? Cache::remember('bih_iblock_ids', static::$cacheMinutes, $callback, static::getCacheDir())
            : $callback();
    }

    /**
     * Директория где хранится кэш.
     *
     * @return string
     */
    protected static function getCacheDir()
    {
        return '/bih_iblock_id';
    }
}
