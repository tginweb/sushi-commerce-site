<?php

namespace Main\Bitrix\UserField;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Main\DI\Containerable;
use Protobuf\Exception;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

/**
 * Class StoreListType
 *
 * Пользовательский тип свойства. Реализует привязку к складу по его XML_ID.
 *
 * @package Adv\AdvApplication\UserProperty
 */
class Enum extends UserTypeBase
{
    use StringColTypeTrait;
    use Containerable;

    const USER_TYPE = 'tg_enum';
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
        return self::BASE_TYPE_STRING;
    }

    /**
     * @return string
     */
    public static function getDescription()
    {
        return 'Словарь';
    }

    /**
     * @inheritDoc
     */
    public static function prepareSettings($userField)
    {
        return [
            'DICTIONARY_ID' => (string)$userField['SETTINGS']['DICTIONARY_ID'],
            'DEFAULT_VALUE' => $userField['SETTINGS']['DEFAULT_VALUE'] ?: '',
        ];
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getFilterHTML($userField, $htmlControl)
    {
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE'], true, $userField);
    }

    /**
     * @param string $inputName
     * @param string | array $selectedValue
     * @return string
     * @throws LoaderException
     */
    protected static function getFormFieldHtml($inputName, $selectedValue, $addEmpty, $userField)
    {

        $items = self::getEnumItems($userField);


        $multiple = strpos($inputName, '[]');

        if ($multiple) {
            if ($selectedValue) {
                $selectedValue = [];
            } else if (is_scalar($selectedValue)) {
                $selectedValue = [$selectedValue];
            }
        }

        $input = '<select style="max-width:250px;" ' . ($multiple ? 'multiple' : '') . ' name="' . $inputName . '">';

        $input .= ($addEmpty) ? '<option value="">нет</option>' : '';

        foreach ($items as $item) {

            if ($multiple || is_array($selectedValue)) {
                $selected = in_array($item['code'], $selectedValue);
            } else {
                $selected = ($item['code'] == $selectedValue);
            }

            $input .= '<option '
                . ($selected ? 'selected' : '')
                . ' value="'
                . $item['code']
                . '">'
                . $item['name']
                . '</option>';
        }

        $input .= '</select>';

        return $input;
    }

    protected static function getEnumItems($userField)
    {
        $dictId = $userField['SETTINGS']['DICTIONARY_ID'];
        //file_put_contents(__DIR__.'/aa.txt', $dictId);
        return static::container()->getEnumService()->getItems($dictId);
    }

    /**
     * @inheritDoc
     * @throws LoaderException
     */
    public static function getAdminListViewHTML($userField, $htmlControl)
    {
        return self::getEnumItemName($userField, $htmlControl['VALUE']);
    }

    protected static function getEnumItemName($userField, $value)
    {
        $dictId = $userField['SETTINGS']['DICTIONARY_ID'];
        return static::container()->getEnumService()->getItemField($dictId, $value, 'name');
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
                $arPrint[] = self::getEnumItemName($userField, $val);
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
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE'], $userField['MANDATORY'] !== 'Y', $userField);
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
        return self::getFormFieldHtml($htmlControl['NAME'], $htmlControl['VALUE'], false, $userField);
    }

    /**
     * @inheritDoc
     */
    public static function getSettingsHTML($userField, $htmlControl, $isVarsFromForm)
    {

        return <<<END
        <tr>
            <td>
                Словарь:
            </td>
            <td>
                <input type="text" name="{$htmlControl['NAME']}[DICTIONARY_ID]" value="{$userField['SETTINGS']['DICTIONARY_ID']}"/>
            </td>
        </tr>
END;

    }
}
