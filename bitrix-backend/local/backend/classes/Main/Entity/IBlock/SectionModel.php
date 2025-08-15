<?php

namespace Main\Entity\IBlock;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use CComponentEngine;
use CFile;
use CIBlock;
use CIBlockSection;
use CUserFieldEnum;
use Main\DI\Containerable;
use Main\Entity\Image\ImageModel;
use Main\Entity\Model\BitrixModel;

class SectionModel extends BitrixModel
{
    use Containerable;

    const IBLOCK_ID = null;

    public static $bxObject;
    public static $objectClass = 'CIBlockSection';

    public $parentSectionsIds;

    public static function getFieldsInfo()
    {
        return [
            'ID' => ['view' => true, 'notNull' => true, 'type' => 'number'],
            'NAME' => ['view' => true, 'type' => 'string'],
            'CODE' => ['view' => true, 'type' => 'string'],
            'SORT' => ['view' => true, 'type' => 'number'],

            'IBLOCK_ID' => ['view' => true, 'type' => 'number',],
            'IBLOCK_CODE' => ['view' => true, 'type' => 'string',],
            'IBLOCK_TYPE_ID' => ['view' => true, 'type' => 'string',],

            'IBLOCK_EXTERNAL_ID' => ['view' => true, 'type' => 'string',],
            'ELEMENT_CNT' => ['view' => true, 'type' => 'nuber',],

            'EXTERNAL_ID' => ['view' => true, 'type' => 'string'],
            'IBLOCK_SECTION_ID' => ['view' => true, 'type' => 'number'],
            'TIMESTAMP_X' => ['view' => true, 'type' => 'datetime',],

            'ACTIVE' => ['view' => true, 'type' => 'boolean'],

            'PICTURE' => ['view' => true, 'type' => 'file'],
            'DETAIL_PICTURE' => ['view' => true, 'type' => 'file'],

            'DESCRIPTION' => ['view' => true, 'type' => 'string',],
            'DESCRIPTION_TYPE' => ['view' => true, 'type' => 'string',],

            'LEFT_MARGIN' => ['view' => true, 'type' => 'number',],
            'RIGHT_MARGIN' => ['view' => true, 'type' => 'number',],

            'DEPTH_LEVEL' => ['view' => true, 'type' => 'number',],
            'SEARCHABLE_CONTENT' => ['view' => true, 'type' => 'text',],

            'SECTION_PAGE_URL' => ['view' => true, 'type' => 'string',],
            'DATE_CREATE' => ['view' => true, 'type' => 'datetime',],

            // private
            'MODIFIED_BY' => ['view' => false, 'type' => 'number',],
            'CREATED_BY' => ['view' => false, 'type' => 'number',],
        ];
    }

    public static function getPropsInfo()
    {
        return [];
    }

    static function getElementModelClass()
    {
        return ElementModel::class;
    }

    public static function iblockId()
    {
        if (static::IBLOCK_ID) {
            return static::IBLOCK_ID;
        } else {
            $id = static::getIblockService()->getSectionIBlockIdByClass(static::class);
            if ($id) {
                return $id;
            }
            return static::getElementModelClass()::iblockId();
        }
    }

    public static function tableEntityId()
    {
        $iblock = static::iblockId();
        return $iblock ? 'IBLOCK_' . static::iblockId() . '_SECTION' : null;
    }

    public static function internalDirectCreate($bxObject, $fields, $resort = true, $updateSearch = true, $resizePictures = false)
    {
        return $bxObject->add($fields, $resort, $updateSearch, $resizePictures);
    }

    public static function getProps()
    {
        $tableEntityId = static::tableEntityId();
        return $tableEntityId ? static::container()->getFieldsService()->getUserFields($tableEntityId) : [];
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

                if ($prop) {

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
                            'fullName' => 'UF_' . $prop['CODE'],
                            'prop' => $prop
                        ] + $info;
                    $result[$code] = $citem;
                }
            }

            static::$attrsInfo[$cls] = $result;
        }

        return static::$attrsInfo[$cls];
    }

    public static function create($fields)
    {
        if (!isset($fields['IBLOCK_ID'])) {
            $fields['IBLOCK_ID'] = static::iblockId();
        }
        return static::internalCreate($fields);
    }

    public function getIblockId()
    {
        return $this['IBLOCK_ID'] ?: static::iblockId();
    }

    public function getDirectChildren(array $filter = [])
    {
        return static::query()
            ->filter($filter)
            ->filter(['SECTION_ID' => $this->id])
            ->select('ID')
            ->getList()
            ->transform(function ($section) {
                return (int)$section['ID'];
            })
            ->all();
    }

    public static function query($iblockId = null)
    {
        return new SectionQuery(static::instantiateObject(), get_called_class(), $iblockId);
    }

    public function getAllChildren(array $filter = [], $sort = ['LEFT_MARGIN' => 'ASC'])
    {
        if (!isset($this->fields['LEFT_MARGIN']) || !isset($this->fields['RIGHT_MARGIN'])) {
            $this->refresh();
        }

        return static::query()
            ->sort($sort)
            ->filter($filter)
            ->filter([
                '!ID' => $this->id,
                '>LEFT_MARGIN' => $this->fields['LEFT_MARGIN'],
                '<RIGHT_MARGIN' => $this->fields['RIGHT_MARGIN'],
            ])
            ->select('ID')
            ->getList()
            ->transform(function ($section) {
                return (int)$section['ID'];
            })
            ->all();
    }

    public function getPanelButtons($options = [])
    {
        return CIBlock::GetPanelButtons(
            $this->getIblockId(),
            0,
            $this->id,
            $options
        );
    }


    public function scopeChildrenOf(SectionQuery $query, SectionModel $section)
    {
        $query->filter['>LEFT_MARGIN'] = $section->fields['LEFT_MARGIN'];
        $query->filter['<RIGHT_MARGIN'] = $section->fields['RIGHT_MARGIN'];
        return $query;
    }

    public function scopeDirectChildrenOf(SectionQuery $query, $section)
    {
        $query->filter['SECTION_ID'] = is_int($section) ? $section : $section->id;

        return $query;
    }

    function getParents($includeCurrent = true)
    {
        $result = [];
        if ($includeCurrent)
            $result[] = $this;
        $parent_section_id = $this->fields['IBLOCK_SECTION_ID'];

        while ($parent_section_id) {
            if ($model = static::getById($parent_section_id)) {
                $parent_section_id = $model['IBLOCK_SECTION_ID'];
                $result[] = $model;
            }
        }

        return $result;
    }

    function getPropsClientData()
    {
        $data = parent::getPropsClientData() + [
                'DATE_CREATE_UNIX' => intval($this['DATE_CREATE_UNIX']),
                'IBLOCK_SECTION_ID' => intval($this['IBLOCK_SECTION_ID']),
                'SORT' => intval($this['SORT']),
                'ELEMENT_CNT' => $this['ELEMENT_CNT']
            ];

        return $data;
    }

    function getIPropertyValue($props = null)
    {
        if (!isset($this->cachedFields['IPROPERTY_VALUES'])) {
            $ipropValues = new SectionValues(
                $this["IBLOCK_ID"],
                $this["ID"]
            );
            $this->cachedFields['IPROPERTY_VALUES'] = $ipropValues->getValues();
        }

        if (!empty($props)) {

            if (is_array($props)) {

                $result = [];

                foreach ($props as $prop) {
                    $result[$prop] = $this->cachedFields['IPROPERTY_VALUES'][$prop];
                }

            } else {

                $result = $this->cachedFields['IPROPERTY_VALUES'][$props];
            }

            return $result;
        }

        return $this->cachedFields['IPROPERTY_VALUES'];
    }

    function getProp($name, $field = 'VALUE', $multiple = false)
    {
        $result = null;

        $prop = strpos($name, 'UF_') === 0 ? $name : 'UF_' . $name;

        switch ($field) {
            case 'VALUE':
                $result = $this->fields[$prop];
                break;
            case 'XML_ID':
                if ($enumId = $this->fields[$prop]) {
                    $enumId = (array)$enumId;
                    $result = [];
                    foreach ($enumId as $id) {
                        if ($enum = $this->getPropEnum($prop, $id))
                            $result[] = $enum['XML_ID'];
                    }
                }
                break;
        }

        if (!$multiple && is_array($result)) {
            $result = $result[0];
        }

        return $result;
    }

    function getPropEnum($name, $enumId)
    {
        static $cache = [];

        $iblockId = $this->fields['IBLOCK_ID'];

        if (!isset($cache[$iblockId][$name])) {
            $cache[$iblockId][$name] = [];
            $property_enums = CUserFieldEnum::GetList(array(), array(
                "USER_FIELD_NAME" => $name,
            ));

            while ($prop = $property_enums->GetNext()) {
                $cache[$iblockId][$name][$prop['ID']] = $prop;
            }
        }

        return $cache[$iblockId][$name][$enumId];
    }

    function getImageSrc()
    {
        if ($image = $this->getImage()) {
            return $image['SRC'];
        }
    }

    function getImage()
    {
        if ($this['PICTURE']) {
            $rs = CFile::GetList(array(), array("ID" => $this['PICTURE']));
            while ($file = $rs->GetNext()) {
                $file["SRC"] = CFile::GetFileSRC($file);
                return new ImageModel($file['ID'], $file);
            }
        }
    }

    function getParentSectionsIds()
    {
        if (!isset($this->parentSectionsIds)) {
            $this->parentSectionsIds = [];
            $rs = CIBlockSection::GetNavChain(0, $this['IBLOCK_SECTION_ID'], array("ID"));
            while ($section = $rs->Fetch()) {
                $this->parentSectionsIds[] = $section['ID'];
            }
        }
        return $this->parentSectionsIds;
    }

    public function getUrl()
    {
        $params = [
            'SECTION_CODE_PATH' => '',
            "ID" => $this["ID"],
            "CODE" => $this["CODE"],
        ];

        if (
            strpos($this["SECTION_PAGE_URL"], 'SECTION_CODE_PATH') !== false ||
            strpos($this["SECTION_PAGE_URL"], 'SECTION_CODE') !== false
        ) {
            $arSectionCodes = [];
            $rsSections = CIBlockSection::GetNavChain($this["IBLOCK_ID"], $this["ID"], array("ID", "IBLOCK_SECTION_ID", "CODE"));
            while ($section = $rsSections->Fetch())
                $arSectionCodes[] = $section["CODE"];

            $params['SECTION_CODE_PATH'] = join('/', $arSectionCodes);
            $params['SECTION_CODE'] = $arSectionCodes[count($arSectionCodes) - 1];
        }

        return $this->evalData('URL', function () use ($params) {
            return '/' . ltrim(CComponentEngine::MakePathFromTemplate($this["SECTION_PAGE_URL"], $params), '/');
        });
    }

    protected function internalUpdate($fields, $fieldsSelectedForSave)
    {
        return !empty($fields) ? static::$bxObject->update($this->id, $fields, true, true, false) : false;
    }

    public function getMenuItemImageId()
    {
        if ($this['PICTURE']) {
            return $this['PICTURE'];
        }
    }

    public function getMenuItemData($element)
    {
        $item = [];

        $item['id'] = 's' . $this['IBLOCK_ID'] . '.' . $this['ID'];

        if ($this['IBLOCK_SECTION_ID']) {
            $parent = 's' . $this['IBLOCK_ID'] . '.' . $this['IBLOCK_SECTION_ID'];
        } else {
            $parent = 's' . $element->fields['IBLOCK_SECTION_ID'];
        }

        $item['imageId'] = $this->getMenuItemImageId();
        $item['label'] = $this['NAME'];
        $item['parent'] = $parent;
        $item['url'] = $this['UF_REPLACE_LINK'] ?? $this->getUrl();
        $item['display'] = [];

        $item['entityType'] = 'product.section';

        return $item;
    }
}
