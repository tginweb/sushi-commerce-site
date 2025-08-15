<?php

namespace Main\Entity\IBlock;

use CComponentEngine;
use CFile;
use CIBlockSection;
use Main\DI\Containerable;
use Main\Entity\Model\BitrixModel;
use TG\Main\Helper;

class ElementModel extends BitrixModel
{
    use Containerable;

    const IBLOCK_ID = null;
    const IBLOCK_VERSION = 2;

    static $generatedModelClasses = [];
    static $bxObject;

    protected static $objectClass = 'CIBlockElement';

    public $sectionsPath;
    public $url;
    public $changedFields = [];
    public $changedFieldsAfterSave = [];
    public $sectionsAreFetched = false;

    public static function create($fields = [])
    {
        if (!isset($fields['IBLOCK_ID'])) {
            $fields['IBLOCK_ID'] = static::getIblockIdOrThrow();
        }
        $model = static::internalCreate($fields);
        if ($model) {
            $model->saveProps();
        }
        return $model;
    }

    public static function getFieldsInfo()
    {
        return [
            'ID' => ['view' => true, 'notNull' => true, 'base' => true, 'type' => 'number'],
            'NAME' => ['view' => true, 'base' => true, 'type' => 'string'],
            'CODE' => ['view' => true, 'base' => true, 'type' => 'string'],
            'SORT' => ['view' => true, 'base' => true, 'type' => 'number'],

            'IBLOCK_ID' => ['view' => true, 'base' => true, 'type' => 'number'],
            'IBLOCK_CODE' => ['view' => true, 'base' => true, 'type' => 'string'],

            'IBLOCK_SECTION_ID' => ['view' => true, 'base' => true, 'type' => 'number'],
            'IBLOCK_SECTION' => ['view' => true, 'type' => 'array'],

            'ACTIVE' => ['view' => true, 'base' => true, 'type' => 'boolean'],
            'ACTIVE_FROM' => ['view' => true, 'type' => 'date'],
            'ACTIVE_TO' => ['view' => true, 'type' => 'number'],

            'TIMESTAMP_X' => ['view' => true, 'type' => 'datetime'],
            'DATE_CREATE' => ['view' => true, 'type' => 'datetime'],
            'CREATED_BY' => ['view' => true, 'type' => 'number'],
            'CREATED_USER_NAME' => ['view' => true, 'type' => 'string'],
            'MODIFIED_BY' => ['view' => true, 'type' => 'number'],

            'PREVIEW_PICTURE' => ['view' => true, 'type' => 'file'],
            'PREVIEW_TEXT' => ['view' => true, 'type' => 'text'],
            'PREVIEW_TEXT_TYPE' => ['view' => true, 'type' => 'string'],

            'DETAIL_PICTURE' => ['view' => true, 'type' => 'file'],
            'DETAIL_TEXT' => ['view' => true, 'type' => 'text', 'fulltextSearch' => true],
            'DETAIL_TEXT_TYPE' => ['view' => true, 'type' => 'string'],

            'SEARCHABLE_CONTENT' => ['view' => ['detail'], 'type' => 'text'],

            'SHOW_COUNTER' => ['view' => true, 'type' => 'number'],
            'SHOW_COUNTER_START' => ['view' => true, 'type' => 'datetime'],

            'TAGS' => ['view' => true, 'type' => 'string'],
            'XML_ID' => ['view' => true, 'type' => 'string'],
            'EXTERNAL_ID' => ['view' => true, 'type' => 'string'],
        ];
    }

    public static function getPropsInfo()
    {
        return [];
    }

    public static function getProps()
    {
        $iblockId = static::iblockId();
        return !empty($iblockId) ? static::container()->getIblockService()->getProps($iblockId, [], true) : [];
    }

    public static function getFieldInfo($code)
    {
        return static::getFieldsInfo()[$code];
    }

    public static function getPropInfo($code)
    {
        $iblockId = static::iblockId();
        return $iblockId ? static::container()->getIblockService()->getProp($iblockId, $code) : null;
    }

    public static function getAttrsInfo()
    {
        $cls = static::class;

        if (!isset(static::$attrsInfo[$cls])) {

            $result = [];
            $fields = static::getFieldsInfo();

            foreach ($fields as $code => $field) {
                $citem = [
                        'attrType' => 'field',
                        'code' => $code,
                        'fullName' => $code
                    ] + $field;
                $result[$code] = $citem;
            }

            $props = static::getProps();
            $propsInfo = static::getPropsInfo();

            foreach ($props as $code => $prop) {

                $info = $propsInfo[$code] ?? [];

                if (!isset($info['view']) && static::PROPS_ALL_PUBLIC) {
                    $info['view'] = true;
                }

                switch ($prop['PROPERTY_TYPE']) {
                    case 'N':
                    case 'F':
                    case 'L':
                    case 'E':
                        $info['type'] = 'number';
                        break;
                    case 'S':
                    case 'D':
                    case 'T':
                    case 'X':
                    default:
                        $info['type'] = 'string';
                }

                $citem = [
                        'attrType' => 'prop',
                        'code' => $prop['CODE'],
                        'fullName' => 'PROPERTY_' . $prop['CODE'],
                        'prop' => $prop
                    ] + $info;
                $result[$code] = $citem;
            }

            static::$attrsInfo[$cls] = $result;
        }

        return static::$attrsInfo[$cls];
    }

    public static function getIblockIdOrThrow()
    {
        $ids = static::iblockId();
        if (empty($ids))
            die('IBLOCK_ID not found ' . static::class);
        return is_array($ids) ? $ids[0] : $ids;
    }

    public static function iblockId()
    {
        if (static::IBLOCK_ID) {
            return static::IBLOCK_ID;
        }
        return static::getIblockService()->getElementIBlockIdByClass(static::class);
    }

    public function getIblockId()
    {
        return $this['IBLOCK_ID'] ?: static::iblockId();
    }

    public function saveProps($selected = [])
    {
        $propertyValues = $this->constructPropertyValuesForSave($selected);
        if (empty($propertyValues)) {
            return false;
        }
        $bxMethod = empty($selected) ? 'setPropertyValues' : 'setPropertyValuesEx';
        static::$bxObject->$bxMethod(
            $this->id,
            $this->getIblockId(),
            $propertyValues
        );
        return true;
    }

    public function constructPropertyValuesForSave($selectedFields = [])
    {
        $propertyValues = [];
        $saveOnlySelected = !empty($selectedFields);
        foreach ($this->fields as $code => $value) {
            if ($saveOnlySelected && !in_array($code, $selectedFields)) {
                continue;
            }
            if (preg_match('/^PROPERTY_(.*)_VALUE$/', $code, $matches) && !empty($matches[1])) {
                $propertyValues[$matches[1]] = $value;
            }
        }
        return $propertyValues;
    }

    public static function internalDirectCreate($bxObject, $fields, $workFlow = false, $updateSearch = true, $resizePictures = false)
    {
        if (!isset($fields['NAME']))
            $fields['NAME'] = 'элемент';
        return $bxObject->add($fields, $workFlow, $updateSearch, $resizePictures);
    }

    public function getComplexProp($name)
    {
        $service = $this->container->getIblockService();
        $iblockId = static::getIblockIdOrThrow();

        $prop = $service->getProp($iblockId, $name);

        if (!empty($prop['USER_TYPE_SETTINGS'])) {
            $userTypeInfo = unserialize($prop['USER_TYPE_SETTINGS']);
            $subProps = [];
            foreach ($userTypeInfo['SUBPROPS'] as $propId) {
                $subProp = $service->getProp($iblockId, $propId, 'ID');
                if ($subProp) {
                    $supPropCode = str_replace($prop['CODE'] . '_', '', $subProp['CODE']);
                    $subProps[$supPropCode] = $subProp;
                }
            }

            $values = $this->getProp($name, 'VALUE', true);

            $result = [];

            foreach ($values as $key => $value) {
                $item = [];
                foreach ($subProps as $subPropAlias => $subProp) {
                    $propVal = $value['SUB_VALUES'][$subProp['CODE']];
                    $val = null;
                    if ($propVal['VALUE_ENUM_ID']) {
                        $val = $propVal['VALUE_ENUM_ID'];
                    } else {
                        $val = $propVal['~VALUE'];
                    }
                    $item[$subPropAlias] = \Main\Helper\Format::getJsValue($val);
                }
                $result[] = $item;
            }
        }

        return $result;
    }

    function getProp($name, $field = 'VALUE', $multiple = false)
    {
        $result = $multiple ? [] : null;
        $field = $field ?: 'VALUE';
        $prop = 'PROPERTY_' . $name;

        switch ($field) {
            case 'ENUM':
                $enumIds = array_filter((array)$this->fields[$prop . '_ENUM_ID']);
                if (!empty($enumIds)) {
                    $enumIds = (array)$enumIds;
                    $result = [];
                    foreach ($enumIds as $id) {
                        $result[] = $this->container->getIblockService()->getPropertyEnumByEnumId($this->fields['IBLOCK_ID'], $name, $id);
                    }
                    $result = array_filter($result);
                }
                break;

            case 'XML_ID':
            case 'ENUM_ID':
                $enumIds = array_filter((array)$this->fields[$prop . '_ENUM_ID']);
                if ($field === 'XML_ID') {
                    if (!empty($enumIds)) {
                        $result = [];
                        foreach ($enumIds as $id) {
                            $enum = $this->container->getIblockService()->getPropertyEnumByEnumId($this->fields['IBLOCK_ID'], $name, $id);
                            if ($enum)
                                $result[] = $enum['XML_ID'];
                        }
                    }
                } else {
                    $result = $enumIds;
                }
                break;
            default:
                if (isset($this->fields[$prop . '_' . $field]))
                    $result = $this->fields[$prop . '_' . $field];
                break;
        }

        if (!$multiple) {
            if (is_array($result) && isset($result[0])) {
                $result = $result[0];
            }
        } else {
            if (!empty($result) && !is_array($result)) {
                $result = (array)$result;
            }
        }
        return $result;
    }

    public static function getModelClassName($iblockId)
    {
        $cls = 'ElementModel' . $iblockId;
        if (!self::$generatedModelClasses[$iblockId]) {
            self::$generatedModelClasses[$iblockId] = true;
            eval('
                class ' . $cls . ' extends ' . ElementModel::class . ' {
                    public static function iblockId()
                    {
                        return ' . $iblockId . ';
                    } 
                }
            ');
        }
        return $cls;
    }

    public function load()
    {
        $this->getFields();
        return $this;
    }

    public function getSections()
    {
        if ($this->sectionsAreFetched) {
            return $this->fields['IBLOCK_SECTION'];
        }
        return $this->refreshSections();
    }

    public function refreshSections()
    {
        if ($this->id === null) {
            return [];
        }

        $cls = static::sectionModel();

        $this->fields['IBLOCK_SECTION'] = [];
        $dbSections = static::$bxObject->getElementGroups($this->id, true);
        while ($section = $dbSections->Fetch()) {

            if ($cls)
                $section = new $cls($section['ID'], $section);

            $this->fields['IBLOCK_SECTION'][] = $section;
        }

        $this->sectionsAreFetched = true;
        return $this->fields['IBLOCK_SECTION'];
    }

    public static function sectionModel()
    {
        return SectionModel::class;
    }

    public function refresh()
    {
        return $this->refreshFields();
    }

    public function refreshFields()
    {
        if ($this->id === null) {
            $this->original = [];

            return $this->fields = [];
        }

        $sectionsBackup = isset($this->fields['IBLOCK_SECTION']) ? $this->fields['IBLOCK_SECTION'] : null;

        $this->fields = static::query()->getById($this->id)->fields;

        if (!empty($sectionsBackup)) {
            $this->fields['IBLOCK_SECTION'] = $sectionsBackup;
        }

        $this->fieldsAreFetched = true;
        $this->original = $this->fields;
        return $this->fields;
    }

    /**
     * @return ElementQuery
     */
    public static function query()
    {
        return new ElementQuery(static::instantiateObject(), get_called_class());
    }

    public function getSection($load = false)
    {
        if ($this->getData('SECTION'))
            return $this->getData('SECTION');

        $fields = $this->getFields();
        if (!$load) {
            return $fields['IBLOCK_SECTION_ID'];
        }

        /** @var SectionModel $sectionModel */
        $sectionModel = static::sectionModel();
        if (!$fields['IBLOCK_SECTION_ID']) {
            return false;
        }

        return $sectionModel::query()->getById($fields['IBLOCK_SECTION_ID']);
    }

    /**
     * @return false|SectionModel
     */
    public function section($load = false)
    {
        $fields = $this->getFields();

        /** @var SectionModel $sectionModel */
        $sectionModel = static::sectionModel();

        return $load
            ? $sectionModel::query()->getById($fields['IBLOCK_SECTION_ID'])
            : new $sectionModel($fields['IBLOCK_SECTION_ID']);
    }

    public function setPropValue($code, $value, $by = 'VALUE')
    {
        switch ($by) {
            case 'XML_ID':
                $enum = $this->container->getIblockService()->getPropertyEnumIdByXmlId(static::iblockId(), $code, $value);
                if ($enum)
                    $value = $enum['ID'];
                break;
        }

        $field = 'PROPERTY_' . $code . '_VALUE';

        if (is_array($value) && empty($value)) {
            $value = false;
            $this->changedFieldsAfterSave[$field] = [];
        }

        $this[$field] = $value;
        $this->changedFields[] = $field;
    }

    function getParentSection()
    {
        $sections = $this->getParentSections();
        return !empty($sections) ? $sections[count($sections) - 1] : null;
    }

    function getParentSections()
    {
        if (!isset($this->sectionsPath)) {
            $this->sectionsPath = [];
            $cls = static::sectionModel();
            $rs = CIBlockSection::GetNavChain($this['IBLOCK_ID'], $this['IBLOCK_SECTION_ID'], array("ID", "CODE", "NAME", "SECTION_PAGE_URL"));
            while ($section = $rs->Fetch()) {
                $this->sectionsPath[] = new $cls($section['ID'], $section);
            }
        }
        return $this->sectionsPath;
    }

    function getRootSection()
    {
        $sections = $this->getParentSections();
        return !empty($sections) ? $sections[0] : null;
    }

    public function getUrl()
    {
        $params = [
            'SECTION_CODE_PATH' => '',
            'SECTION_CODE' => '',
            'SECTION_ID' => '',
            "ELEMENT_ID" => $this["ID"],
            "ID" => $this["ID"],
            "ELEMENT_CODE" => $this["CODE"],
        ];

        if (strpos($this["DETAIL_PAGE_URL"], 'SECTION') !== false) {
            $arSectionCodes = [];
            $arSectionIds = [];
            $rsSections = CIBlockSection::GetNavChain($this["IBLOCK_ID"], $this["IBLOCK_SECTION_ID"], array("ID", "IBLOCK_SECTION_ID", "CODE"));

            while ($section = $rsSections->Fetch()) {
                $arSectionCodes[] = $section["CODE"];
                $arSectionIds[] = $section["ID"];
            }
            if ($arSectionCodes) {
                $params['SECTION_CODE_PATH'] = join('/', $arSectionCodes);
                $params['SECTION_CODE'] = $arSectionCodes[count($arSectionCodes) - 1];
                $params['SECTION_ID'] = $arSectionIds[count($arSectionIds) - 1];
            }
        }

        return $this->evalData('URL', function () use ($params) {
            return '/' . ltrim(CComponentEngine::MakePathFromTemplate($this["DETAIL_PAGE_URL"], $params), '/');
        });
    }

    public function propFilesDelete($codes)
    {
        foreach ($codes as $code)
            $this->propFileDelete($code);
    }

    public function propFileDelete($code, $deleteFileIds = null)
    {
        if ($deleteFileIds) {
            $deleteFileIds = (array)$deleteFileIds;
            $propFileIds = $this->getProp($code, 'VALUE', true);

            foreach ($propFileIds as $index => $fileId) {
                if (in_array($fileId, $deleteFileIds)) {
                    $fileId = array('FILE_ID' => $fileId, 'file_id' => $fileId, 'del' => 'Y');
                } else {
                    $fileId = CFile::MakeFileArray($fileId);
                }
                $propFileIds[$index] = $fileId;
            }
            $this->setPropValue($code, $propFileIds);
        } else {
            $this->setPropValue($code, array('del' => 'Y'));
        }
    }

    public function propFileUpdate($code, $inputFileIds = null, $op = 'set', $multiple = false)
    {
        if ($op === 'add') {
            $multiple = true;
        }

        $result = [];

        if (!empty($inputFileIds))
            $inputFileIds = (array)$inputFileIds;

        if ($op === 'add') {

            $inputFileIds = array_combine($inputFileIds, $inputFileIds);

            $propFileIds = $this->getProp($code, 'VALUE', true);

            foreach ($propFileIds as $fileId) {
                if ($inputFileIds[$fileId]) {
                    unset($inputFileIds[$fileId]);
                }
                $arFile = CFile::MakeFileArray($fileId);
                $result[] = $arFile;
            }

            foreach ($inputFileIds as $fileId) {
                $arFile = CFile::MakeFileArray($fileId);
                $result[] = $arFile;
            }

        } else if ($op === 'set') {

            if (!$multiple) {
                $fileId = $inputFileIds[0];
                $result = CFile::MakeFileArray($fileId);
            } else {
                if (!empty($inputFileIds)) {
                    foreach ($inputFileIds as $fileId) {
                        $arFile = CFile::MakeFileArray($fileId);
                        $result[] = $arFile;
                    }
                } else {
                    $result = array('del' => 'Y');
                }
            }

        } else if ($op === 'delete') {

            if ($multiple) {
                if (!empty($inputFileIds)) {
                    $propFileIds = $this->getProp($code, 'VALUE', true);

                    foreach ($propFileIds as $index => $fileId) {
                        if (in_array($fileId, $inputFileIds)) {
                            $result[$index] = array('FILE_ID' => $fileId, 'file_id' => $fileId, 'del' => 'Y');
                        } else {
                            $result[$index] = CFile::MakeFileArray($fileId);
                        }
                    }

                } else {
                    $result = array('del' => 'Y');
                }
            } else {
                $result = [];
            }
        }

        $this->setPropValue($code, $result);
    }

    public function setPropValues($values)
    {
        foreach ($values as $code => $value) {
            $this->setPropValue($code, $value);
        }
    }

    public function setFieldsValue($fields)
    {
        foreach ($fields as $code => $value)
            $this->setFieldValue($code, $value);
    }

    public function setFieldValue($code, $value, $by = 'VALUE')
    {
        $this[$code] = $value;
        $this->changedFields[] = $code;
    }

    public function save($fields = [])
    {
        $res = parent::save(array_merge($fields, $this->changedFields));

        $this->changedFields = [];

        if (!empty($this->changedFieldsAfterSave)) {
            foreach ($this->changedFieldsAfterSave as $field => $value) {
                $this[$field] = $value;
            }
            $this->changedFieldsAfterSave = [];
        }

        return $res;
    }

    function getName()
    {
        return $this['NAME'];
    }

    protected function afterFill()
    {
        $this->normalizePropertyFormat();
    }

    protected function normalizePropertyFormat()
    {
        if (empty($this->fields['PROPERTIES'])) {
            return;
        }
        foreach ($this->fields['PROPERTIES'] as $code => $prop) {
            $this->fields['PROPERTY_' . $code . '_VALUE'] = $prop['VALUE'];
            $this->fields['~PROPERTY_' . $code . '_VALUE'] = $prop['~VALUE'];
            $this->fields['PROPERTY_' . $code . '_DESCRIPTION'] = $prop['DESCRIPTION'];
            $this->fields['~PROPERTY_' . $code . '_DESCRIPTION'] = $prop['~DESCRIPTION'];
            $this->fields['PROPERTY_' . $code . '_VALUE_ID'] = $prop['PROPERTY_VALUE_ID'];
        }
    }

    protected function fieldShouldNotBeSaved($field, $value, $selectedFields)
    {
        $blacklistedFields = [
            'ID',
            'IBLOCK_ID',
            'PROPERTIES',
            'PROPERTY_VALUES',
        ];
        return (!empty($selectedFields) && !in_array($field, $selectedFields))
            || in_array($field, $blacklistedFields)
            || ($field[0] === '~');
        //|| (substr($field, 0, 9) === 'PROPERTY_');
    }

    protected function internalUpdate($fields, $fieldsSelectedForSave, $workFlow = false, $updateSearch = true, $resizePictures = false)
    {
        $fields = $fields ?: [];
        foreach ($fields as $key => $value) {
            if (substr($key, 0, 9) === 'PROPERTY_') {
                unset($fields[$key]);
            }
        }
        $result = !empty($fields) ? static::$bxObject->update($this->id, $fields, $workFlow, $updateSearch, $resizePictures) : false;
        $savePropsResult = $this->saveProps($fieldsSelectedForSave);
        $result = $result || $savePropsResult;
        return $result;
    }

    protected function getValueFromLanguageField($field)
    {
        $key = $field . '_' . $this->getCurrentLanguage() . '_VALUE';
        return isset($this->fields[$key]) ? $this->fields[$key] : null;
    }

    public function getImageSrc()
    {
        if ($image = $this->getImage()) {
            return $image['SRC'];
        }
    }

    public function getImage()
    {
        return $this->getData('DETAIL_PICTURE') ?: $this->getData('PREVIEW_PICTURE');
    }
}
