<?php

namespace Main\Bitrix\IblockPropType;

use CUserTypeString;
use Main\DI\Containerable;
use voku\helper\Hooks;
use function TG\Main\Bitrix\IblockPropType\htmlspecialcharsbx;

class EnumPropType extends CUserTypeString
{
    use Containerable;

    // инициализация пользовательского свойства для главного модуля
    static function GetIBlockPropertyDescription()
    {

        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "tg_enum",
            "DESCRIPTION" => "Словарь",
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
                    <input type="text" name="' . $arHtmlControl["NAME"] . '[DICTIONARY_ID]" size="20" maxlength="225" value="' . $settings['DICTIONARY_ID'] . '">
                </td>
            </tr>
            ';

        return $result;
    }

    function PrepareSettings($arProperty)
    {
        $settings = $arProperty['USER_TYPE_SETTINGS'];

        $newsettings = [];

        $newsettings['DICTIONARY_ID'] = !empty($settings['DICTIONARY_ID']) ? $settings['DICTIONARY_ID'] : '';

        return array('USER_TYPE_SETTINGS' => $newsettings);
    }

    public function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
    {
        $settings = self::PrepareSettings($arProperty);

        $settings = $settings['USER_TYPE_SETTINGS'];
        $multiple = $settings['MULTIPLE'];
        $selectedValue = $arValue['VALUE'];

        $options = [
            '' => ''
        ];

        if ($settings['DICTIONARY_ID']) {
            $items = static::getEnumItems($settings['DICTIONARY_ID']);
            foreach ($items as $itemValue => $item) {
                if ($multiple || is_array($selectedValue)) {
                    $selected = in_array($itemValue, $selectedValue);
                } else {
                    $selected = $itemValue == $selectedValue;
                }
                $options[$itemValue] = [
                    'selected' => $selected,
                    'name' => $item['name'],
                ];
            }
        }

        $strResult = '<select type="text" name="' . htmlspecialcharsbx($strHTMLControlName['VALUE']) . '" id="' . $strHTMLControlName['VALUE'] . '">';
        foreach ($options as $value => $option) {
            $strResult .= '<option ' . ($option['selected'] ? 'selected' : '') . ' value="' . $value . '">' . $option['name'] . '</option>';
        }
        $strResult .= '</select>';

        return $strResult;
    }

    protected static function getEnumItems($dictionaryId)
    {
        return static::container()->getEnumService()->getItems($dictionaryId);
    }

    static public function getHooks()
    {
        return Hooks::getInstance();
    }
}

