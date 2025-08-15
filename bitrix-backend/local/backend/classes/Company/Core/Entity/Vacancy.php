<?php

namespace Company\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class Vacancy extends ElementModel
{
    /**
     * Получить только активные вакансии
     */
    public static function getActiveList()
    {
        return static::query()
            ->filter(['ACTIVE' => 'Y'])
            ->withViewList()
            ->getList();
    }

    /**
     * Получить вакансию по символьному коду
     */
    public static function getByCode($code)
    {
        return static::query()
            ->filter(['CODE' => $code])
            ->withViewList()
            ->first();
    }

    /**
     * Получить URL вакансии
     */
    public function getUrl()
    {
        return '/vacancy/' . $this['ID'];
    }
}
