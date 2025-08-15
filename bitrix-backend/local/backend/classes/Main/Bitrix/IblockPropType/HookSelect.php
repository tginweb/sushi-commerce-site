<?php

namespace Main\Bitrix\IblockPropType;

use CUserTypeString;
use voku\helper\Hooks;
use function TG\Main\Bitrix\IblockPropType\htmlspecialcharsbx;

class HookSelect extends CUserTypeString
{
    // инициализация пользовательского свойства для главного модуля
    static function GetIBlockPropertyDescription()
    {

        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "tg_hook_select",
            "DESCRIPTION" => "Выбор из хука",
            'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
            'GetAdminListViewHTML' => array(__CLASS__, 'GetAdminListViewHTML'),
            "GetSettingsHTML" => array(__CLASS__, "GetSettingsHTML"),
            "PrepareSettings" => array(__CLASS__, "PrepareSettings"),
        );
    }

    // инициализация пользовательского свойства для инфоблока

    function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "tg_hook_select",
            "CLASS_NAME" => static::class,
            "DESCRIPTION" => "ВЫБОР из списка - фильтр",
            "BASE_TYPE" => "string",
        );
    }

    function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {
        if (strlen($arHtmlControl["VALUE"]) > 0)
            return $arHtmlControl["VALUE"];
        else
            return '&nbsp;';
    }

    function GetSettingsHTML($arProperty = false, $arHtmlControl, &$arPropertyFields)
    {
        $arPropertyFields = array(
            "HIDE" => array(
                "FILTRABLE",
                "ROW_COUNT",
                "COL_COUNT",
                "SMART_FILTER",
                "WITH_DESCRIPTION",
                "HINT",
                "MULTIPLE_CNT",
                "MULTIPLE",
                "IS_REQUIRED"
            ),
            "SET" => array(
                "FILTRABLE" => "N",
                "SMART_FILTER" => "N",
                "IS_REQUIRED" => "N",
                "MULTIPLE" => "N",
                "SECTION_PROPERTY" => "Y"
            ),
        );

        $settings = self::PrepareSettings($arProperty);
        $settings = $settings['USER_TYPE_SETTINGS'];

        $result = '';

        $result .= '
            <tr>
                <td>Filter Name:</td>
                <td>
                    <input type="text" name="' . $arHtmlControl["NAME"] . '[FILTER_NAME]" size="20" maxlength="225" value="' . $settings['FILTER_NAME'] . '">
                </td>
            </tr>
            ';

        return $result;
    }

    function PrepareSettings($arProperty)
    {
        $settings = $arProperty['USER_TYPE_SETTINGS'];

        $newsettings = [];

        $newsettings['FILTER_NAME'] = !empty($settings['FILTER_NAME']) ? $settings['FILTER_NAME'] : '';

        return array('USER_TYPE_SETTINGS' => $newsettings);
    }

    public function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
    {
        $settings = self::PrepareSettings($arProperty);

        $settings = $settings['USER_TYPE_SETTINGS'];

        $options = [
            '' => ''
        ];

        if ($settings['FILTER_NAME']) {

            $items = static::getHooks()->apply_filters($settings['FILTER_NAME'], []);

            foreach ($items as $itemKey => $item) {

                $label = null;

                if (is_array($item)) {
                    $label = $item['NAME'];
                } else if (is_scalar($item)) {
                    $label = $item;
                }

                if ($label)
                    $options[$itemKey] = $label;
            }
        }

        $strResult = '<select type="text" name="' . htmlspecialcharsbx($strHTMLControlName['VALUE']) . '" id="' . $strHTMLControlName['VALUE'] . '">';

        foreach ($options as $value => $option) {
            $title = is_array($option) ? $option['NAME'] : $option;
            $strResult .= '<option ' . ($arValue['VALUE'] == $value ? 'selected' : '') . ' value="' . $value . '">' . $title . '</option>';
        }

        $strResult .= '</select>';

        return $strResult;
    }


    static public function getHooks()
    {
        return Hooks::getInstance();
    }
}

