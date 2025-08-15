<?php

namespace Main\Bitrix\UserField;

use CFileMan;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class Html extends UserTypeBase
{
    use StringColTypeTrait;

    const USER_TYPE = 'tg_html';

    public static function getDescription()
    {
        return 'HTML код';
    }

    public static function getUserTypeId()
    {
        return static::USER_TYPE;
    }

    /**
     * @return string
     */
    public static function getBaseType()
    {
        return self::BASE_TYPE_STRING;
    }

    public static function getSettingsHTML($userField, $htmlControl, $varsFromForm)
    {
        $result = '';
        $value = '';
        if ($varsFromForm) {
            $value = $GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE'];
        } elseif (is_array($userField)) {
            $value = $userField['SETTINGS']['DEFAULT_VALUE'];
        }

        $result .= '
        <tr>
          
        </tr>
		';

        return $result;
    }

    public static function prepareSettings($userField)
    {
        return [
            'DEFAULT_VALUE' => $userField['SETTINGS']['DEFAULT_VALUE'] ?: '',
        ];
    }

    public static function getAdminListEditHTML($userField, $htmlControl)
    {
        return static::getEditFormHTML($userField, $htmlControl);
    }

    public static function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        ob_start();
        CFileMan::AddHTMLEditorFrame($arHtmlControl['NAME'], $arHtmlControl['VALUE'], false, 'html', ['height' => 450, 'width' => '100%']);
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

    public static function getAdminListViewHTML($userField, $htmlControl)
    {
        if (!empty($htmlControl['VALUE'])) {
            return $htmlControl['VALUE'];
        }
        return '&nbsp;';
    }
}

