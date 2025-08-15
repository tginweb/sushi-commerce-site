<?php

namespace Main\Lib\Orm;

use Bitrix\Main\DB\ArrayResult;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Query\Result;

class StubTable extends DataManager
{
    static function getList($parametrs = [])
    {
        $query = static::query();
        return new Result($query, new ArrayResult([]));
    }
}
