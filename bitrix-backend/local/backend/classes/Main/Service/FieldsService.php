<?php

namespace Main\Service;

use CAdminForm;
use CMain;
use CUserFieldEnum;
use CUserTypeManager;
use function TG\Main\Service\GetMessage;
use function TG\Main\Service\htmlspecialcharsbx;
use const TG\Main\Service\LANGUAGE_ID;

class FieldsService extends BaseService
{
    public $userFieldsByEntityId = [];
    public $listEnums = [];

    function register($scopes = [])
    {
    }

    function getEnumCodeById($entityName, $fieldName, $id)
    {
        $enums = $this->getEnums($entityName, $fieldName);
        $enumsFlipped = array_flip($enums);
        return $enumsFlipped[$id] ?? null;
    }

    function getEnums($entityName, $fieldName)
    {
        $entityName = $this->container->getHlBlockService()->getEntityFieldsName($entityName);
        global $USER_FIELD_MANAGER;
        if (!isset($this->listEnums[$entityName])) {
            $this->listEnums[$entityName] = [];
            $arFields = $USER_FIELD_MANAGER->GetUserFields($entityName);
            $arField = $arFields[$fieldName];
            if ($arField) {
                $obEnum = new CUserFieldEnum;
                $rsEnum = $obEnum->GetList(
                    [],
                    [
                        'USER_FIELD_ID' => $arField['ID']
                    ]
                );
                while ($arEnum = $rsEnum->Fetch()) {
                    $this->listEnums[$entityName][$arEnum['XML_ID']] = $arEnum['ID'];
                }
            }
        }
        return $this->listEnums[$entityName];
    }

    function getUserField($entityId, $fieldName)
    {
        $fields = $this->getUserFields($entityId);
        return $fields[$fieldName] ?? null;
    }

    function getUserFields($entityId)
    {
        global $USER_FIELD_MANAGER;

        if (!isset($this->userFieldsByEntityId[$entityId])) {
            $propsDb = $USER_FIELD_MANAGER->GetUserFields($entityId);
            $items = [];
            foreach ($propsDb as $code => $prop) {
                $code = preg_replace('/UF\_/i', '', $code);
                $prop['CODE'] = $code;
                $items[$code] = $prop;
            }
            $this->userFieldsByEntityId[$entityId] = $items;
        }

        return $this->userFieldsByEntityId[$entityId];
    }

    function getEnumIdByCode($entityName, $fieldName, $code)
    {
        $enums = $this->getEnums($entityName, $fieldName);
        return $enums[$code] ?? null;
    }

    function showAdminFormUserFieldsWithReadyData(CAdminForm $tabControl, $PROPERTY_ID, $readyData, $bVarsFromForm, $primaryIdName = 'VALUE_ID', $onlyFields = [])
    {
        /**
         * @global CMain $APPLICATION
         * @global CUserTypeManager $USER_FIELD_MANAGER
         */
        global $USER_FIELD_MANAGER, $APPLICATION;

        if ($USER_FIELD_MANAGER->GetRights($PROPERTY_ID) >= "W") {
            $tabControl->BeginCustomField("USER_FIELDS_ADD", GetMessage("admin_lib_add_user_field"));
            ?>
            <tr>
                <td colspan="2" align="left">
                    <a href="/bitrix/admin/userfield_edit.php?lang=<? echo LANGUAGE_ID ?>&amp;ENTITY_ID=<? echo urlencode($PROPERTY_ID) ?>&amp;back_url=<? echo urlencode($APPLICATION->GetCurPageParam($tabControl->name . '_active_tab=user_fields_tab', array($tabControl->name . '_active_tab'))) ?>"><? echo $tabControl->GetCustomLabelHTML() ?></a>
                </td>
            </tr>
            <?
            $tabControl->EndCustomField("USER_FIELDS_ADD", '');
        }

        $arUserFields = $USER_FIELD_MANAGER->getUserFieldsWithReadyData($PROPERTY_ID, $readyData, LANGUAGE_ID, false, $primaryIdName);

        if (!empty($onlyFields)) {
            foreach ($onlyFields as $FIELD_NAME) {
                $arUserFieldsFiltered[$FIELD_NAME] = $arUserFields[$FIELD_NAME];
            }
        } else {
            $arUserFieldsFiltered = $arUserFields;
        }

        foreach ($arUserFieldsFiltered as $FIELD_NAME => $arUserField) {
            if (!empty($onlyFields) && !in_array($FIELD_NAME, $onlyFields))
                continue;

            $arUserField["VALUE_ID"] = intval($readyData[$primaryIdName]);
            if (array_key_exists($FIELD_NAME, $tabControl->arCustomLabels))
                $strLabel = $tabControl->arCustomLabels[$FIELD_NAME];
            else
                $strLabel = $arUserField["EDIT_FORM_LABEL"] ? $arUserField["EDIT_FORM_LABEL"] : $arUserField["FIELD_NAME"];
            $arUserField["EDIT_FORM_LABEL"] = $strLabel;

            $tabControl->BeginCustomField($FIELD_NAME, $strLabel, $arUserField["MANDATORY"] == "Y");

            if (isset($_REQUEST['def_' . $FIELD_NAME]))
                $arUserField['SETTINGS']['DEFAULT_VALUE'] = $_REQUEST['def_' . $FIELD_NAME];

            echo $USER_FIELD_MANAGER->GetEditFormHTML($bVarsFromForm, $GLOBALS[$FIELD_NAME], $arUserField);

            $form_value = $GLOBALS[$FIELD_NAME];
            if (!$bVarsFromForm)
                $form_value = $arUserField["VALUE"];
            elseif ($arUserField["USER_TYPE"]["BASE_TYPE"] == "file")
                $form_value = $GLOBALS[$arUserField["FIELD_NAME"] . "_old_id"];

            $hidden = "";
            if (is_array($form_value)) {
                foreach ($form_value as $value)
                    $hidden .= '<input type="hidden" name="' . $FIELD_NAME . '[]" value="' . htmlspecialcharsbx($value) . '">';
            } else {
                $hidden .= '<input type="hidden" name="' . $FIELD_NAME . '" value="' . htmlspecialcharsbx($form_value) . '">';
            }
            $tabControl->EndCustomField($FIELD_NAME, $hidden);
        }
    }
}


