<?php

namespace Main\Entity\IBlock;

use _CIBElement;
use CDBResult;
use CIBlock;
use CIBlockElement;
use Main\DI\Containerable;
use Main\Entity\Query\BitrixQuery;
use Protobuf\Exception;


class ElementQuery extends BitrixQuery
{
    public static $cIblockObject;
    static $complexCache = [];

    public $sort = ['SORT' => 'ASC'];
    public $groupBy = false;
    public $iblockId;

    public $withSections = false;

    protected $iblockVersion;

    protected $standardFields = [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'CODE',
    ];

    public function __construct($bxObject, $modelName, $iblockId = null, $collectionClass = null)
    {
        static::instantiateCIblockObject();

        parent::__construct($bxObject, $modelName);

        if ($iblockId)
            $this->iblockId = $iblockId;
        else
            $this->iblockId = $modelName::iblockId();

        $this->iblockVersion = $modelName::IBLOCK_VERSION ?: 2;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    public static function instantiateCIblockObject()
    {
        if (static::$cIblockObject) {
            return static::$cIblockObject;
        }

        if (class_exists('CIBlock')) {
            return static::$cIblockObject = new CIBlock();
        }

        throw new Exception('CIblock object initialization failed');
    }

    static function getComplexSingle($id, $view = null, $modelCollection = null, $preserveEmpty = false)
    {
        $keys = [$id];
        $res = static::getComplexList($keys, $view, $modelCollection, $preserveEmpty);
        return !empty($res) ? current($res) : null;
    }

    static function getComplexList($keys, $view = null, $modelCollection = null, $preserveEmpty = false)
    {
        $iblockWithoutClass = [];
        $iblockToClass = [];
        $itemToIblock = [];
        $itemsByClass = [];

        $keysIndex = [];

        if (empty($keys)) {
            return [];
        }

        foreach ($keys as &$item) {

            $data = null;

            if ($item) {
                list ($type, $id) = explode(':', $item);

                if (!$id) {
                    $id = $type;
                    $type = null;
                }
                list ($id, $itemView, $itemField) = explode('.', $id);

                $data = [
                    'id' => $id,
                    'view' => $itemView ?? $view ?? 'public',
                ];

                if (!$type) {
                    $itemToIblock[$id] = null;
                } else if (is_numeric($type)) {
                    $iblockToClass[$type] = null;
                    $data['iblockId'] = $type;
                } else {
                    $data['class'] = $type;
                }
            }

            $keysIndex[] = $data;
        }

        if (!empty($itemToIblock)) {
            $rs = CIBlockElement::getList([], ['ID' => array_keys($itemToIblock)], false, false, ['ID', 'IBLOCK_ID']);
            while ($row = $rs->fetch()) {
                $itemToIblock[$row['ID']] = $row['IBLOCK_ID'];
                $iblockToClass[$row['IBLOCK_ID']] = null;
            }
        }

        if ($iblockToClass) {
            foreach ($iblockToClass as $iblockId => $v) {
                $class = Containerable::container()->getIblockService()->getElementIBlockClassById($iblockId, null, null);
                if (!$class) {
                    $class = 'query:' . $iblockId;
                }
                $iblockToClass[$iblockId] = $class;
            }
        }

        foreach ($keysIndex as &$item) {
            if ($item) {
                if (!$item['class']) {
                    if ($item['iblockId']) {
                        $item['class'] = $iblockToClass[$item['iblockId']];
                    } else {
                        if ($iblockId = $itemToIblock[$item['id']]) {
                            $item['class'] = $iblockToClass[$iblockId];
                        }
                    }
                }
                if ($item['class']) {
                    $itemsByClass[$item['class']][$item['view']][$item['id']] = $item['id'];
                }
            }
        }

        $elements = [];

        if (!empty($itemsByClass)) {

            foreach ($itemsByClass as $cls => $views) {

                if (preg_match('/query\:/', $cls)) {
                    list(, $iblockId) = explode(':', $cls);
                    $modelCls = ElementModel::getModelClassName($iblockId);
                    $query = new ElementQuery(ElementModel::instantiateObject(), $modelCls, $iblockId, ElementCollection::class);
                } else {
                    if (!class_exists($cls))
                        continue;
                    $query = $cls::query();
                }

                $nonCachedIds = [];

                foreach ($views as $view => $ids) {

                    foreach ($ids as $id) {
                        $element = self::$complexCache[$id . '-' . $view];
                        if (isset($element)) {
                            $elements[$cls][$view][$id] = $element;
                        } else {
                            $nonCachedIds[$id] = $id;
                        }
                    }

                    if (!empty($nonCachedIds)) {

                        $res = $query
                            ->filter(['ID' => array_filter($ids)])
                            ->withView($view)
                            ->getList()
                            ->keyBy('ID');

                        foreach ($res as $id => $element) {
                            self::$complexCache[$id . '-' . $view] = $element;
                            unset($nonCachedIds[$id]);
                        }

                        foreach ($nonCachedIds as $id) {
                            self::$complexCache[$id . '-' . $view] = false;
                        }

                        $elements[$cls][$view] = $res;
                    }
                }
            }
        }

        $items = array_map(function ($key) use ($elements) {
            if ($key && isset($elements[$key['class']][$key['view']][$key['id']])) {
                return $elements[$key['class']][$key['view']][$key['id']];
            }
            return null;
        }, $keysIndex);

        $items = array_values($items);

        if (!$preserveEmpty)
            $items = array_filter($items);

        if ($modelCollection)
            $items = new $modelCollection($items);

        return $items;
    }

    public function withView($viewmode = [])
    {
        parent::withView($viewmode);
        foreach ($this->getModelAttrsFiltered($viewmode) as $name => $attr) {
            if ($attr['fullName'])
                $this->select[$attr['fullName']] = $attr['fullName'];
        }
        return $this;
    }

    public function groupBy($value)
    {
        $this->groupBy = $value;
        return $this;
    }

    public function withSections()
    {
        $this->withSections = true;
        return $this;
    }

    /**
     * @return ElementModel
     */
    public function getByCode($code)
    {
        $this->filter['CODE'] = $code;
        return $this->first();
    }

    /**
     * @return ElementModel
     */
    public function getByExternalId($id)
    {
        $this->filter['EXTERNAL_ID'] = $id;

        return $this->first();
    }

    public function count()
    {
        if ($this->queryShouldBeStopped) {
            return 0;
        }

        $filter = $this->normalizeFilter();
        $queryType = "ElementQuery::count";

        $callback = function () use ($filter) {
            return (int)$this->bxObject->GetList(false, $filter, []);
        };

        return $this->handleCacheIfNeeded(compact('filter', 'queryType'), $callback);
    }

    public function sort($by, $order = 'ASC')
    {
        switch ($by) {
            case 'price':
                $by = 'catalog_PRICE_1';
                break;
            default:
                if ($this->model::getPropInfo($by)) {
                    $by = 'PROPERTY_' . $by;
                }
        }

        return parent::sort($by, $order);
    }

    public function filterProps($props, $onlyPrefixed = false)
    {
        foreach ($this->filterPropsProcess($props) as $propCode => $propValue) {
            $this->filter([$propCode => $propValue]);
        }
        return $this;
    }

    /**
     * @return ElementCollection
     */
    protected function loadModels($collectionMethod = null)
    {
        $sort = $this->sort;
        $filter = $this->normalizeFilter();

        $groupBy = $this->groupBy;
        $navigation = $this->navigation;
        $select = $this->normalizeSelect();
        $queryType = 'ElementQuery::getList';
        $fetchUsing = $this->fetchUsing;
        $keyBy = $this->keyBy;

        list($select, $chunkQuery) = $this->multiplySelectForMaxJoinsRestrictionIfNeeded($select);

        $callback = function () use ($sort, $filter, $groupBy, $navigation, $select, $chunkQuery, $collectionMethod) {

            $result = $this->resultProcess($this->loadCollection($sort, $filter, $groupBy, $navigation, $select, $chunkQuery));

            if ($collectionMethod)
                return $result->{$collectionMethod}();
            else
                return $result;
        };

        $cacheKeyParams = compact('sort', 'filter', 'groupBy', 'navigation', 'select', 'queryType', 'keyBy', 'fetchUsing', 'collectionMethod');

        return $this->handleCacheIfNeeded($cacheKeyParams, $callback);
    }

    protected function normalizeFilter()
    {
        if ($this->iblockId)
            $this->filter['IBLOCK_ID'] = $this->iblockId;
        return $this->filter;
    }

    protected function normalizeSelect()
    {
        if ($this->fieldsMustBeSelected()) {
            $this->select = array_merge($this->standardFields, $this->select);
        }

        $this->select[] = 'ID';
        $this->select[] = 'IBLOCK_ID';

        return $this->clearSelectArray();
    }

    protected function multiplySelectForMaxJoinsRestrictionIfNeeded($select)
    {
        if (!$this->propsMustBeSelected()) {
            return [$select, false];
        }

        $chunkSize = 50;
        $props = $this->fetchAllPropsForSelect();

        if ($this->iblockVersion !== 1 || (count($props) <= $chunkSize)) {
            return [array_merge($select, $props), false];
        }

        // начинаем формировать селекты из свойств
        $multipleSelect = array_chunk($props, $chunkSize);

        // добавляем в каждый селект поля "несвойства"
        foreach ($multipleSelect as $i => $partOfProps) {
            $multipleSelect[$i] = array_merge($select, $partOfProps);
        }

        return [$multipleSelect, true];
    }

    protected function fetchAllPropsForSelect()
    {
        $props = [];

        foreach ((array)$this->iblockId as $iblockId) {

            $rsProps = static::$cIblockObject->GetProperties($iblockId);

            while ($prop = $rsProps->Fetch()) {
                $props[] = 'PROPERTY_' . $prop['CODE'];
            }
        }

        return array_unique($props);
    }

    public function loadCollection($sort, $filter, $groupBy, $navigation, $select, $chunkQuery)
    {
        if ($this->isEmpty)
            return new $this->collectionClass([]);

        if ($chunkQuery) {

            $itemsChunks = [];

            foreach ($select as $chunkIndex => $selectForChunk) {
                $rsItems = $this->bxObject->GetList($sort, $filter, $groupBy, $navigation, $selectForChunk);
                while ($arItem = $this->performFetchUsingSelectedMethod($rsItems)) {

                    $modelClass = $this->getModelClass($arItem);
                    $model = new $modelClass($arItem['ID'], $arItem);

                    $this->addItemToResults($itemsChunks[$chunkIndex], $model);
                }
            }

            $items = $this->mergeChunks($itemsChunks);

        } else {

            $items = [];

            $rsItems = $this->bxObject->GetList($sort, $filter, $groupBy, $navigation, $select);

            while ($arItem = $this->performFetchUsingSelectedMethod($rsItems)) {

                $modelClass = $this->getModelClass($arItem);
                $this->addItemToResults($items, new $modelClass($arItem['ID'], $arItem));
            }
        }

        $models = new $this->collectionClass($items);

        if ($this->incResultRefs) {
            foreach ($models as $model) {
                $model->setQueryRef($this);
                $model->setCollectionRef($models);
            }
        }

        if ($this->withSections) {
            $sectionClass = $this->modelName::getSectionClass();
            $sectionIds = $models->pluckArray('IBLOCK_SECTION_ID', 'IBLOCK_SECTION_ID');
            $sections = $sectionClass::query()->filter(['ID' => $sectionIds])->getList();
            foreach ($models as $model) {
                $section = $sections[$model['IBLOCK_SECTION_ID']];
                if ($section) {
                    $model['IBLOCK_SECTION_CODE'] = $section['CODE'];
                    $model->setData('SECTION', $sections[$model['IBLOCK_SECTION_ID']]);
                }
            }
        }

        return $models;
    }

    /**
     * Choose between Fetch() and GetNext($bTextHtmlAuto, $useTilda) and then fetch
     *
     * @param CDBResult $rsItems
     * @return array|false
     */
    protected function performFetchUsingSelectedMethod($rsItems)
    {
        $arItem = parent::performFetchUsingSelectedMethod($rsItems);

        if ($arItem) {
            if (in_array('PROPS_ALL', $this->select)) {
                $obItem = new _CIBElement;
                $obItem->fields = $arItem;

                foreach ($obItem->GetProperties() as $prop) {
                    if ($prop['VALUE'] && is_array($prop['VALUE'])) {

                        $arItem['PROPERTY_' . $prop['CODE'] . '_VALUE'] = [];
                        $arItem['PROPERTY_' . $prop['CODE']] = [];

                        foreach ($prop['~VALUE'] as $index => $val) {
                            $arItem['PROPERTY_' . $prop['CODE'] . '_VALUE'][] = $val;
                            $arItem['PROPERTY_' . $prop['CODE']][$index]['VALUE'] = $val;

                            if (isset($prop['~DESCRIPTION'][$index])) {
                                $arItem['PROPERTY_' . $prop['CODE'] . '_DESCRIPTION'][] = $prop['~DESCRIPTION'][$index];
                                $arItem['PROPERTY_' . $prop['CODE']][$index]['DESCRIPTION'] = $prop['~DESCRIPTION'][$index];
                            }
                        }
                    }
                }
            }
        }
        return $arItem;
    }

    public function getModelClass($arItem = null)
    {
        if ($arItem) {
            $classByElement = $this->container->applyFiltersCached('element-model-class:' . $arItem['IBLOCK_CODE'], []);
            if ($classByElement[$arItem['ID']])
                return $classByElement[$arItem['ID']];
            if ($classByElement[$arItem['CODE']])
                return $classByElement[$arItem['CODE']];
        }

        return $this->modelName;
    }

    protected function mergeChunks($chunks)
    {
        $items = [];
        foreach ($chunks as $chunk) {
            foreach ($chunk as $k => $item) {
                if (isset($items[$k])) {
                    $item->fields['_were_multiplied'] = array_merge((array)$items[$k]->fields['_were_multiplied'], (array)$item->fields['_were_multiplied']);
                    $items[$k]->fields = (array)$item->fields + (array)$items[$k]->fields;
                } else {
                    $items[$k] = $item;
                }
            }
        }
        return $items;
    }

    public function filter($filter)
    {
        parent::filter($this->filterPropsProcess($filter, true));
        return $this;
    }

    public function filterPropsProcess($filter, $onlyPrefixed = false)
    {
        $pattern = $onlyPrefixed ? '/([\@]+)?(PROP\_|PROPERTY\_)(.+?)(\_(XML\_ID|VALUE))/' : '/([\@]+)?(PROP\_|PROPERTY\_)?(.+?)(\_(XML\_ID|VALUE))/';

        $result = [];

        foreach ($filter as $propCode => $propValue) {

            if (is_numeric($propCode) && is_array($propValue)) {

                $propValue = $this->filterPropsProcess($propValue, $onlyPrefixed);

            } else if (strtoupper($propCode) !== 'LOGIC' && preg_match($pattern, $propCode, $mt)) {

                $propMod = $mt[1];
                $propCode = $mt[3];
                $propField = $mt[5];

                switch ($propField) {
                    case 'XML_ID':
                        if (is_array($propValue)) {
                            $enumIds = [];
                            foreach ($propValue as $val) {
                                $enumIds[] = static::getIblockService()->getPropertyEnumIdByXmlId($propCode, $val, $this->iblockId);
                            }
                            $propValue = $enumIds;
                        } else {
                            $enumId = static::getIblockService()->getPropertyEnumIdByXmlId($propCode, $propValue, $this->iblockId);
                            $propValue = $enumId;
                        }
                        $propCode = $propMod . 'PROPERTY_' . $propCode;
                        break;
                    default:
                        $propCode = $propMod . 'PROPERTY_' . $propCode;
                }
            }

            $result[$propCode] = $propValue;
        }

        return $result;
    }

    public function getFilters($view = [])
    {
        $filters = [
            'ID' => ['type' => 'number', 'client' => true],
            'NAME' => ['type' => 'string', 'client' => true],
            'CODE' => ['type' => 'string', 'client' => true],
            'SORT' => ['type' => 'number', 'client' => true],
            'IBLOCK_ID' => ['type' => 'number', 'client' => true],
            'IBLOCK_CODE' => ['type' => 'string', 'client' => true],
            'IBLOCK_SECTION_ID' => ['type' => 'number', 'client' => true],
            'ACTIVE' => ['type' => 'boolean', 'client' => true],
        ];

        /*
        $attrs = $this->getModelAttrsFiltered($view);

        foreach ($attrs as $name => $attr) {
            $filters[$name] = $attr;
            if ($attr['attrType'] === 'prop') {
                $propType = $attr['prop']['PROPERTY_TYPE'];
                switch ($propType) {
                    case 'L':
                        $filters[$name . '_CODE'] = [
                                'type' => 'string',
                                'filterValueTransformer' => [static::class, 'listFilterValueTransformer'],
                            ] + $attr;
                        break;
                }
            }
        }
        */

        return $filters;
    }

    public static function listFilterValueTransformer($val, $op, $attr)
    {
        return static::container()->getIblockService()->getPropertyEnumIdByCode($attr['prop']['IBLOCK_ID'], $attr['prop']['ID'], $val);
    }

    public function active($value = true)
    {
       $this->filter['ACTIVE'] = $value ? 'Y' : 'N';
       return $this;
    }
}
