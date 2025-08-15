<?php

namespace Main\Service;

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyFeatureTable;
use Bitrix\Main\Loader;
use CCatalog;
use CIBlock;
use Exception;
use Main\Entity\IBlock\PropertyModel;
use TG\Main\Helper;

class IBlockService extends BaseService
{
    public array $iblocks;
    public array $iblocksByCode;

    public array $iblockMap = [
        'elementIdByClass' => [],
        'elementClassById' => [],

        'sectionIdByClass' => [],
        'sectionClassById' => [],

        'idsByRole' => [],
    ];

    public array $propsById = [];
    public array $propsByIBlock = [];
    public array $propsTree = [];

    public array $enumsByPropertyId = [];
    public array $enumsByIBlock = [];
    public array $enumsTree = [];

    function onBuildEntityTypes($list)
    {
        $iblocks = $this->getIblocks();

        die(json_encode($iblocks));

        foreach ($iblocks as $iblock) {

        }
        return $list;
    }

    function getIBlocksSchema()
    {
        return $this->getIblocks();
    }

    function getIBlock($selector, $field = null)
    {
        $iblock = $this->getIBlocks([$selector])[$selector];
        return $iblock ? ($field ? ($iblock[$field] ?? null) : $iblock) : null;
    }

    function getIBlocks($selectors = [], $byCode = null)
    {
        $selectors = \Main\Helper\Common::toArray($selectors);

        if (isset($this->iblocks)) {
            $items = $this->iblocks;
            $itemsByCode = $this->iblocksByCode;
        } else {
            $cache = $this->container->getConfigService()->get('IBLOCK.CACHE_IBLOCKS');

            if ($cache) {
                $cacheProvider = $this->container->getCacheService()->getProvider();
                $cacheItem = $cacheProvider->getItem('iblock');
                if (!$cacheItem->isHit()) {
                    $items = $this->loadIblocks();
                    $cacheItem->set($items);
                    $cacheProvider->saveItem($cacheItem);
                } else {
                    $items = $cacheItem->get();
                }
            } else {
                $items = $this->loadIBlocks();
            }

            foreach ($items as $i => $item) {
                $items[$i] = $this->indexIblock($item);
            }

            $itemsByCode = array_reduce($items, function ($map, $item) {
                $map[$item['CODE'] ?: $item['ID']] = $item;
                return $map;
            }, []);
        }

        if (!empty($selectors)) {
            $result = [];
            foreach ($selectors as $selector) {
                if ($byCode !== false && isset($itemsByCode[$selector])) {
                    $result[$selector] = $itemsByCode[$selector];
                } else if (isset($items[$selector])) {
                    $result[$selector] = $items[$selector];
                }
            }
            return $result;
        } else {
            return $byCode ? $itemsByCode : $items;
        }
    }

    function loadIBlocks($selectors = [])
    {
        $this->container->getGraphqlService()->debugDataPush('load_iblocks', $selectors);

        $targets = array_reduce($selectors, function ($map, $selector) {
            is_numeric($selector) ? $map['ID'][] = $selector : $map['CODE'][] = $selector;
            return $map;
        }, [
            'ID' => [],
            'CODE' => [],
        ]);

        $rows = [];
        $filter = [];

        if (!empty($targets['ID']))
            $filter['ID'] = $targets['ID'];

        if (!empty($targets['CODE']))
            $filter['CODE'] = $targets['CODE'];

        $rs = IblockTable::getList([
            'select' => ['ID', 'CODE', 'NAME', 'LIST_PAGE_URL', 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL', 'ACTIVE'],
            'filter' => $filter,
            'order' => ['ID' => 'ASC']
        ]);

        Loader::includeModule('catalog');

        while ($row = $rs->fetch()) {
            if (!$row['CODE'])
                continue;

            $cat = CCatalog::GetById($row['ID']);
            if ($cat) {
                $row['CATALOG'] = $cat;
            }
            $rows[$row['ID']] = $row;
        }

        return $rows;
    }

    function indexIBlock($iblock)
    {
        $id = $iblock['ID'];

        if (!isset($iblock['WITH_SCHEMA'])) {
            $configSchema = $this->container->getConfigService()->get('IBLOCK.SCHEMA', []);
            $iblock += ($configSchema[$id] ?: []) + [
                    'WITH_SCHEMA' => true
                ];
        }

        if (!isset($this->iblocks[$id])) {

            $this->iblocks[$id] = $iblock;
            $this->iblocksByCode[$iblock['CODE']] = $iblock;

            $this->iblockMap['elementIdByClass'][$iblock['ELEMENT_CLASS']] = $id;
            $this->iblockMap['elementClassById'][$id] = $iblock['ELEMENT_CLASS'];

            $this->iblockMap['sectionIdByClass'][$iblock['SECTION_CLASS']] = $id;
            $this->iblockMap['sectionClassById'][$id] = $iblock['SECTION_CLASS'];

            $this->iblockMap['idsByRole'][$iblock['ROLE']][$id] = $id;
        }

        return $iblock;
    }

    function throw($msg)
    {
        throw new Exception($msg);
    }

    function getElementIBlockIdByClass($class, $throw = false)
    {
        $this->getIblocks();
        $id = $this->iblockMap['elementIdByClass'][$class];
        if (!$id && $throw)
            $this->throw('Not found IBlock ID for class ' . $class);
        return $id;
    }

    function getSectionIBlockIdByClass($class, $throw = false)
    {
        $this->getIblocks();
        $id = $this->iblockMap['sectionIdByClass'][$class];
        if (!$id && $throw)
            $this->throw('Not found IBlock ID for class ' . $class);
        return $id;
    }

    function getElementIBlockClassById($id, $throw = false)
    {
        $this->getIblocks();
        $class = $this->iblockMap['elementClassById'][$id];
        if (!$class && $throw)
            $this->throw('Not found IBlock element class for id ' . $id);
        return $class;
    }

    function getSectionIBlockClassById($id, $throw = false)
    {
        $this->getIblocks();
        $class = $this->iblockMap['sectionClassById'][$id];
        if (!$class && $throw)
            $this->throw('Not found IBlock section class for id ' . $id);
        return $class;
    }

    function getIBlockIdByRole($role)
    {
        $this->getIBlocksSchema();
        $ids = $this->iblockMap['idsByRole'][$role] ?? null;
        if (isset($ids)) {
            return array_values($ids)[0];
        }
    }

    function loadPropertyEnums($iblockIds = [], $propertyIds = null)
    {
        $iblockIds = \Main\Helper\Common::toArray($iblockIds);
        $propertyIds = \Main\Helper\Common::toArray($propertyIds);

        $this->container->getGraphqlService()->debugDataPush('load_enums', [
            \Main\Helper\Common::toScalar($iblockIds),
            \Main\Helper\Common::toScalar($propertyIds)
        ]);

        $items = [];
        $filter = [

        ];

        if (!empty($iblockIds)) {
            $filter['IBLOCK_ID'] = $iblockIds;
        }

        if (!empty($propertyIds)) {
            $filter = array_reduce($propertyIds, function ($map, $id) {
                if (is_numeric($id)) {
                    $map['PROPERTY_ID'][] = $id;
                } else {
                    $map['CODE'][] = $id;
                }
                return $map;
            }, $filter);
        }

        $property_enums = $this->loadPropertyEnumsNative(
            [
                "DEF" => "DESC",
                "SORT" => "ASC"
            ],
            $filter
        );

        while ($item = $property_enums->Fetch()) {
            $items[$item['ID']] = $item;
        }


        return $items;
    }

    public function loadPropertyEnumsNative($arOrder = array("SORT" => "ASC", "VALUE" => "ASC"), $arFilter = array())
    {
        global $DB;

        $arSqlSearch = array();
        foreach ($arFilter as $key => $val) {
            if ($key[0] == "!") {
                $key = substr($key, 1);
                $bInvert = true;
            } else {
                $bInvert = false;
            }

            $key = strtoupper($key);
            switch ($key) {
                case "CODE":
                    $arSqlSearch[] = CIBlock::FilterCreate("P.CODE", $val, "string", $bInvert);
                    break;
                case "IBLOCK_ID":
                    $arSqlSearch[] = CIBlock::FilterCreate("P.IBLOCK_ID", $val, "number", $bInvert);
                    break;
                case "DEF":
                    $arSqlSearch[] = CIBlock::FilterCreate("BEN.DEF", $val, "string_equal", $bInvert);
                    break;
                case "EXTERNAL_ID":
                    $arSqlSearch[] = CIBlock::FilterCreate("BEN.XML_ID", $val, "string_equal", $bInvert);
                    break;
                case "VALUE":
                case "XML_ID":
                case "TMP_ID":
                    $arSqlSearch[] = CIBlock::FilterCreate("BEN." . $key, $val, "string", $bInvert);
                    break;
                case "PROPERTY_ID":
                    $arSqlSearch[] = CIBlock::FilterCreate("P.ID", $val, "number", $bInvert);
                    break;
                case "PROPERTY_ACTIVE":
                    $arSqlSearch[] = CIBlock::FilterCreate("P.ACTIVE", $val, "string_equal", $bInvert);
                    break;
                case "ID":
                case "SORT":
                    $arSqlSearch[] = CIBlock::FilterCreate("BEN." . $key, $val, "number", $bInvert);
                    break;
            }
        }

        $strSqlSearch = "";
        foreach (array_filter($arSqlSearch) as $sqlCondition)
            $strSqlSearch .= " AND  (" . $sqlCondition . ") ";

        $arSqlOrder = array();
        foreach ($arOrder as $by => $order) {
            $order = strtolower($order) != "asc" ? "desc" : "asc";
            $by = strtoupper($by);
            switch ($by) {
                case "ID":
                case "PROPERTY_ID":
                case "VALUE":
                case "XML_ID":
                case "EXTERNAL_ID":
                case "DEF":
                    $arSqlOrder[$by] = "BEN." . $by . " " . $order;
                    break;
                case "PROPERTY_SORT":
                    $arSqlOrder[$by] = "P.SORT " . $order;
                    break;
                case "PROPERTY_CODE":
                    $arSqlOrder[$by] = "P.CODE " . $order;
                    break;
                default:
                    $arSqlOrder["SORT"] = " BEN.SORT " . $order;
                    break;
            }
        }

        if (!empty($arSqlOrder))
            $strSqlOrder = "ORDER BY " . implode(", ", $arSqlOrder);
        else
            $strSqlOrder = "";

        $strSql = "
			SELECT
				BEN.*,
				BEN.XML_ID as EXTERNAL_ID,
				P.NAME as PROPERTY_NAME,
				P.CODE as PROPERTY_CODE,
				P.IBLOCK_ID as IBLOCK_ID,
				P.SORT as PROPERTY_SORT
			FROM
				b_iblock_property_enum BEN,
				b_iblock_property P		
			WHERE
				BEN.PROPERTY_ID=P.ID
			$strSqlSearch
			$strSqlOrder
		";

        $rs = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);
        return new \CDBResult($rs);
    }

    function getPropertyEnumIdByCode($iblockId, $propertyId, $code)
    {
        $enums = $this->getPropertyEnums($iblockId, $propertyId);

        $enumsByCode = array_reduce($enums, function ($map, $item) {
            $map[$item['XML_ID']] = $item;
            return $map;
        }, []);

        if (is_array($code)) {
            return array_map(function ($c) use ($enumsByCode) {
                return isset($enumsByCode[$c]) ? $enumsByCode[$c]['ID'] : null;
            }, $code);
        } else {
            return isset($enumsByCode[$code]) ? $enumsByCode[$code]['ID'] : null;
        }
    }

    function getPropertyEnums($iblockId, $propertyIds = [])
    {
        $enums = [];

        $iblock = $this->getIBlock($iblockId);

        if (!$iblock)
            throw new Exception('iblock not found');

        $iblockId = $iblock['ID'];

        $propertyIds = \Main\Helper\Common::toArray($propertyIds);

        $missingPropertyIds = [];
        $missingPropertyIdsAll = false;

        if (empty($propertyIds)) {
            if (isset($this->enumsByIBlock[$iblockId])) {
                $enums += $this->enumsByIBlock[$iblockId];
            } else {
                $missingPropertyIdsAll = true;
            }
        } else {
            foreach ($propertyIds as $propertyId) {
                if (isset($this->enumsTree[$iblockId][$propertyId])) {
                    $enums += $this->enumsTree[$iblockId][$propertyId]['BY_ID'];
                } else {
                    $missingPropertyIds[$propertyId] = $propertyId;
                }
            }
        }

        if (!empty($missingPropertyIds) || $missingPropertyIdsAll) {

            $cache = $this->container->getConfigService()->get('IBLOCK.CACHE_ENUMS');

            $enumsLoaded = [];

            if ($cache) {

                $cacheProvider = $this->container->getCacheService()->getProvider();

                if (!empty($missingPropertyIds)) {

                    foreach ($missingPropertyIds as $propertyId) {
                        $cacheItem = $cacheProvider->getItem('iblock_props_enums_' . $iblockId . '_' . $propertyId);
                        if ($cacheItem->isHit()) {
                            $enumsLoaded = array_merge($enumsLoaded, $cacheItem->get());
                        } else {
                            $loadResult = $this->loadPropertyEnums($iblockId, $propertyId);

                            //  $cacheItem->set($loadResult);
                            //  $cacheProvider->saveItem($cacheItem);
                            $enumsLoaded = array_merge($enumsLoaded, $loadResult);
                        }
                    }

                } else {
                    $cacheItem = $cacheProvider->getItem('iblock_props_enums_' . $iblockId);
                    if ($cacheItem->isHit()) {
                        $enumsLoaded = array_merge($enumsLoaded, $cacheItem->get());
                    } else {
                        $loadResult = $this->loadPropertyEnums($iblockId);
                        $cacheItem->set($loadResult);
                        $cacheProvider->saveItem($cacheItem);
                        $enumsLoaded = array_merge($enumsLoaded, $loadResult);
                    }
                }

            } else {
                $enumsLoaded = $this->loadPropertyEnums($iblockId);
            }

            foreach ($enumsLoaded as $enum) {
                if (
                    empty($propertyIds) ||
                    in_array($enum['PROPERTY_ID'], $propertyIds) ||
                    in_array($enum['PROPERTY_CODE'], $propertyIds)
                ) {
                    $enums[$enum['ID']] = $enum;
                }
                $this->indexEnum($enum);
            }
        }

        return $enums;
    }

    function indexEnum($enum)
    {
        $id = $enum['ID'];

        if (!isset($this->enumsByPropertyId[$id])) {

            $this->enumsByPropertyId[$enum['PROPERTY_ID']][$id] = &$enum;
            $this->enumsByIBlock[$enum['IBLOCK_ID']][$id] = &$enum;

            $this->enumsTree[$enum['IBLOCK_ID']][$enum['PROPERTY_ID']]['BY_ID'][$id] = &$enum;
            $this->enumsTree[$enum['IBLOCK_ID']][$enum['PROPERTY_ID']]['BY_XML_ID'][$enum['XML_ID']] = &$enum;

            $this->enumsTree[$enum['IBLOCK_ID']][$enum['PROPERTY_CODE']]['BY_ID'][$id] = &$enum;
            $this->enumsTree[$enum['IBLOCK_ID']][$enum['PROPERTY_CODE']]['BY_XML_ID'][$enum['XML_ID']] = &$enum;
        }
    }

    function getPropertyEnumByEnumId($iblockId, $propertyId, $enumId)
    {
        $this->getPropertyEnums($iblockId, $propertyId);
        return $this->enumsTree[$iblockId][$propertyId]['BY_ID'][$enumId] ?? null;
    }

    function getPropertyEnumIdByXmlId($iblockId, $propertyId, $xmlId)
    {
        $this->getPropertyEnums($iblockId, $propertyId);
        return $this->enumsTree[$iblockId][$propertyId]['BY_XML_ID'][$xmlId] ?? null;
    }

    function getIblockClientInfo($iblock, $role = null)
    {
        return [
            'name' => $iblock['NAME'],
            'type' => $iblock['TYPE'],
            'role' => $role ?: ($iblock['CATALOG'] ? 'catalog' : null),
            'url' => [
                'index' => $iblock['LIST_PAGE_URL'],
                'view' => $iblock['DETAIL_PAGE_URL'],
                'section' => $iblock['SECTION_PAGE_URL'],
            ],
        ];
    }

    function loadProp($iblockId, $propId)
    {
        $props = $this->loadProps($iblockId, $propId);
        if (empty($props)) {
            return $props[array_keys($props)[0]];
        }
    }

    function loadProps($iblockIds = [], $propsIds = [])
    {
        $iblockIds = \Main\Helper\Common::toArray($iblockIds);
        $propsIds = \Main\Helper\Common::toArray($propsIds);

        $this->container->getGraphqlService()->debugDataPush('load_props', [
            \Main\Helper\Common::toScalar($iblockIds),
            \Main\Helper\Common::toScalar($propsIds)
        ]);

        $runtime = [
            'IBLOCK' => [
                'data_type' => IblockTable::class,
                'reference' => [
                    '=this.IBLOCK_ID' => 'ref.ID',
                ],
                'join_type' => 'left'
            ],
        ];

        $filter = [
            'ACTIVE' => 'Y',
        ];

        if (empty($iblockIds) && !empty($propsIds)) {
            $filter['ID'] = $propsIds;
        } else {
            $filter = array_reduce($iblockIds, function ($map, $iblockId) {
                is_numeric($iblockId) ? $map['IBLOCK_ID'][] = $iblockId : $map['IBLOCK.CODE'][] = $iblockId;
                return $map;
            }, $filter);
        }

        $props = PropertyModel::query()
            ->select([
                'ID',
                'IBLOCK_CODE' => 'IBLOCK.CODE'
            ])
            ->filter($filter)
            ->runtime($runtime)
            ->getList()
            ->keyBy('ID');

        $featuresAvailable = false;

        if (class_exists(PropertyFeatureTable::class)) {
            $rsFeatures = PropertyFeatureTable::getList([
                'select' => ['PROPERTY_ID', 'MODULE_ID', 'FEATURE_ID', 'IS_ENABLED'],
                'filter' => [
                    '=PROPERTY.IBLOCK_ID' => $iblockIds,
                    '=PROPERTY.ACTIVE' => 'Y'
                ],
                'order' => ['PROPERTY_ID' => 'ASC']
            ]);
            while ($row = $rsFeatures->fetch()) {
                $propId = (int)$row['PROPERTY_ID'];
                if ($props[$propId]) {
                    $props[$propId]->fields['FEATURES'][$row['FEATURE_ID']] = $row['IS_ENABLED'] === 'Y';
                }
            }
            $featuresAvailable = true;
        }

        foreach ($props as $prop) {
            $prop->fields['VIEW'] = [];
            if ($featuresAvailable) {
                if ($prop['FEATURES']) {
                    if ($prop['FEATURES']['LIST_PAGE_SHOW'])
                        $prop->fields['VIEW'][] = 'list';
                    if ($prop['FEATURES']['DETAIL_PAGE_SHOW'])
                        $prop->fields['VIEW'][] = 'detail';
                }
            } else {
                $prop->fields['VIEW'][] = 'list';
                $prop->fields['VIEW'][] = 'detail';
            }
        }

        return $props;
    }

    /**
     * @return PropertyModel
     */
    function getProp($iblockId, $propId)
    {
        $this->getProps($iblockId, $propId);
        return $this->propsTree[$iblockId][$propId] ?? null;
    }

    function mapIblockIds($idsOrCodes = [], $filterNotExists = false)
    {
        $this->getIBlocks();

        $result = array_map(function ($id) {
            if (is_numeric($id)) {
                return $id;
            } else if (isset($this->iblocksByCode[$id])) {
                return $this->iblocksByCode[$id]['ID'];
            }
        }, \Main\Helper\Common::toArray($idsOrCodes));

        if ($filterNotExists) {
            $result = array_filter($result, function ($id) {
                return isset($this->iblocks[$id]);
            });
        }

        return $result;
    }

    function getProps($iblockId, $propertyIds = [], $byCode = null)
    {
        $iblock = $this->getIBlock($iblockId);

        if (!$iblock) {
            return [];
            //throw new Exception('iblock not found');
        }

        $iblockId = $iblock['ID'];

        $propertyIds = \Main\Helper\Common::toArray($propertyIds);

        $missingPropertyIds = [];
        $missingPropertyIdsAll = false;

        $props = [];

        if (empty($propertyIds)) {
            if (isset($this->propsByIBlock[$iblockId])) {
                $props += $this->propsByIBlock[$iblockId];
            } else {
                $missingPropertyIdsAll = true;
            }
        } else {
            foreach ($propertyIds as $propertyId) {
                $prop = $this->propsTree[$iblockId][$propertyId];
                if (isset($prop)) {
                    $props[$prop['ID']] = $prop;
                } else {
                    $missingPropertyIds[$propertyId] = $propertyId;
                }
            }
        }

        if (!empty($missingPropertyIds) || $missingPropertyIdsAll) {

            $loadedProps = [];

            $cache = $this->container->getConfigService()->get('IBLOCK.CACHE_PROPS');

            if ($cache) {
                $cacheProvider = $this->container->getCacheService()->getProvider();
                $cacheItem = $cacheProvider->getItem('iblock_props_' . $iblockId);
                if ($cacheItem->isHit()) {
                    $loadedProps = array_merge($loadedProps, $cacheItem->get());
                } else {
                    $loadResult = $this->loadProps($iblockId)->all();
                    $cacheItem->set($loadResult);
                    $cacheProvider->saveItem($cacheItem);
                    $loadedProps = array_merge($loadedProps, $loadResult);
                }
            } else {
                $loadedProps = $this->loadProps($iblockId);
            }

            foreach ($loadedProps as $prop) {
                if (empty($propertyIds) || in_array($prop['ID'], $propertyIds) || in_array($prop['CODE'], $propertyIds)) {
                    $props[$prop['ID']] = $prop;
                }
                $this->indexProp($prop);
            }
        }

        $propsByCode = array_reduce($props, function ($map, &$item) {
            $map[$item['CODE'] ?: $item['ID']] = $item;
            return $map;
        }, []);

        if (!empty($propertyIds)) {
            $result = [];
            foreach ($propertyIds as $propertyId) {
                if (is_numeric($propertyId) && isset($props[$propertyId])) {
                    $result[$propertyId] = $props[$propertyId];
                } else if (isset($propsByCode[$propertyId])) {
                    $result[$propertyId] = $propsByCode[$propertyId];
                }
            }
            return $result;
        } else {
            return $byCode ? $propsByCode : $props;
        }
    }

    function indexProp($prop)
    {
        $id = $prop['ID'];

        if (isset($this->propsById[$id]))
            return;

        $this->propsById[$id] = $prop;

        $this->propsByIBlock[$prop['IBLOCK_ID']][$id] = &$prop;

        $this->propsTree[$prop['IBLOCK_ID']][$id] = &$prop;
        $this->propsTree[$prop['IBLOCK_ID']][$prop['CODE']] = &$prop;

        $this->propsTree[$prop['IBLOCK_CODE']][$id] = &$prop;
        $this->propsTree[$prop['IBLOCK_CODE']][$prop['CODE']] = &$prop;
    }
}


