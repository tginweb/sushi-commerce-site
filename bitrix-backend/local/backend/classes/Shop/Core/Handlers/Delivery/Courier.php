<?php

namespace Shop\Core\Handlers\Delivery;

class Courier
{
    function Init()
    {
        return array(
            /* Основное описание */
            "SID" => "courier",
            "NAME" => "Доставка курьером по зонам",
            "DESCRIPTION" => "",
            "DESCRIPTION_INNER" => "",
            "BASE_CURRENCY" => "RUB",
            "HANDLER" => __FILE__,

            /* Методы обработчика */
            "DBGETSETTINGS" => array(static::class, "GetSettings"),
            "DBSETSETTINGS" => array(static::class, "SetSettings"),
            "GETCONFIG" => array(static::class, "GetConfig"),
            "COMPABILITY" => array(static::class, "Compability"),
            "CALCULATOR" => array(static::class, "Calculate"),

            /* Список профилей доставки */
            "PROFILES" => array(
                "courier" => array(
                    "TITLE" => "По городу",
                    "DESCRIPTION" => "Доставка в течении дня",
                    "RESTRICTIONS_WEIGHT" => array(0), // без ограничений
                    "RESTRICTIONS_SUM" => array(0), // без ограничений
                ),
            )
        );
    }

    /**
     * настройки обработчика
     */
    function GetConfig()
    {
        $arConfig = array(
            "CONFIG_GROUPS" => array(),
            "CONFIG" => array(),
        );

        return $arConfig;
    }

    /**
     * подготовка настроек для занесения в базу данных
     */
    function SetSettings($arSettings)
    {
        foreach ($arSettings as $key => $value) {
            if (strlen($value) > 0) {
                $arSettings[$key] = doubleval($value);
            } else {
                unset($arSettings[$key]);
            }
        }

        return serialize($arSettings);
    }

    /**
     * подготовка настроек, полученных из базы данных
     */
    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    /**
     * метод проверки совместимости
     */
    function Compability($arOrder, $arConfig)
    {
        return array('courier');
    }

    /**
     * собственно, рассчет стоимости
     */
    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        $vorder = \Shop\Core\Entity\VorderCurrent::instance();

        return array(
            "RESULT" => "OK",
            "VALUE" => 100
        );
    }


}
