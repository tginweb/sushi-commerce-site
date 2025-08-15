<?php

namespace Main\Bitrix\UserField;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CGroup;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\IntegerColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

/**
 * Class StoreListType
 *
 * Пользовательский тип свойства. Реализует привязку к складу по его XML_ID.
 *
 * @package Adv\AdvApplication\UserProperty
 */
class UserGroup extends UserTypeBase
{
    use IntegerColTypeTrait;

    const USER_TYPE = 'tg_user_group';
    /**
     * @var array
     */
    protected static $options = [];
    /**
     * @var array
     */
    private static $userGroups;

    /**
     * @throws LoaderException
     */
    public static function init()
    {
        if (Loader::includeModule('main')) {
            parent::init();
        }
    }


    /**
     * @return string
     */
    public static function getUserTypeId()
    {
        return self::USER_TYPE;
    }

    /**
     * @return string
     */
    public static function getBaseType()
    {
        return self::BASE_TYPE_INT;
    }

    /**
     * @return string
     */
    public static function getDescription()
    {
        return 'Привязка к Группе пользователя';
    }

    /**
     * @inheritDoc
     */
    public static function prepareSettings($userField)
    {
        return [
            'DEFAULT_VALUE' => $userField['SETTINGS']['DEFAULT_VALUE'] ?: '',
        ];
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getFilterHTML($userField, $htmlControl)
    {
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE']);
    }

    /**
     * @param string $inputName
     * @param string $selectedValue
     * @param bool $addEmpty
     *
     * @return string
     * @throws LoaderException
     */
    protected static function getFormFieldHtml($inputName, $selectedValue = [], $addEmpty = true)
    {
        $items = self::getUserGroupList();

        $multiple = strpos($inputName, '[]');


        $input = '<select style="max-width:250px;" ' . ($multiple ? 'multiple' : '') . ' name="' . $inputName . '">';

        $input .= ($addEmpty) ? '<option value="">нет</option>' : '';

        foreach ($items as $item) {

            if ($multiple || is_array($selectedValue)) {
                $selected = in_array($item['ID'], $selectedValue);
            } else {
                $selected = ($item['ID'] == $selectedValue);
            }

            $input .= '<option '
                . ($selected ? 'selected' : '')
                . ' value="'
                . $item['ID']
                . '">'
                . $item['NAME']
                . '</option>';
        }
        $input .= '</select>';

        return $input;
    }

    /**
     * @return array
     * @throws LoaderException
     */
    protected static function getUserGroupList()
    {
        if (is_array(self::$userGroups)) {
            return self::$userGroups;
        }

        self::$userGroups = [];

        $dbGroups = CGroup::GetList(
            ($b = "c_sort"),
            ($o = "asc"),
            array("ANONYMOUS" => "N")
        );

        while ($arGroup = $dbGroups->Fetch()) {
            self::$userGroups[$arGroup['ID']] = $arGroup;
        }

        return self::$userGroups;
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getAdminListViewHTML($userField, $htmlControl)
    {
        return self::getUserGroupName($htmlControl['VALUE']);
    }

    /**
     * @param string $sid
     *
     * @return string
     * @throws LoaderException
     */
    protected static function getUserGroupName($sid)
    {
        $sid = trim($sid);
        if ($sid == '') {
            return '';
        }

        $groups = self::getUserGroupList();
        if (array_key_exists($sid, $groups) && array_key_exists('NAME', $groups[$sid])) {
            return trim($groups[$sid]['NAME']);
        }

        return trim($sid);
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getAdminListViewHTMLMulty($userField, $htmlControl)
    {
        if (!empty($htmlControl['VALUE']) && is_array($htmlControl['VALUE'])) {
            $arPrint = [];
            foreach ($htmlControl['VALUE'] as $val) {
                $arPrint[] = self::getUserGroupName($val);
            }

            return implode(' / ', $arPrint);
        }

        return '&nbsp;';
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getAdminListEditHTML($userField, $htmlControl)
    {
        return static::getEditFormHTML($userField, $htmlControl);
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getEditFormHTML($userField, $htmlControl)
    {
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE']);
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getAdminListEditHTMLMulty($userField, $htmlControl)
    {
        return static::getEditFormHTMLMulty($userField, $htmlControl);
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getEditFormHTMLMulty($userField, $htmlControl)
    {
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE']);
    }

    /**
     * @inheritDoc
     */
    public static function getSettingsHTML($userField, $htmlControl, $isVarsFromForm)
    {
        return '';
    }
}
