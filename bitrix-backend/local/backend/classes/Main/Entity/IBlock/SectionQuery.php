<?php

namespace Main\Entity\IBlock;

use CIBlockSection;
use Illuminate\Support\Collection;
use Main\DI\Containerable;
use Main\Entity\Query\BitrixQuery;
use TG\Main\Helper;

class SectionQuery extends BitrixQuery
{
    /**
     * Query sort.
     *
     * @var array
     */
    public $sort = ['SORT' => 'ASC'];

    /**
     * Query bIncCnt.
     * This is sent to getList directly.
     *
     * @var array|false
     */
    public $countElements = false;

    /**
     * Iblock id.
     *
     * @var int
     */
    protected $iblockId;

    /**
     * List of standard entity fields.
     *
     * @var array
     */
    protected $standardFields = [
        'ID',
        'CODE',
        'EXTERNAL_ID',
        'IBLOCK_ID',
        'IBLOCK_SECTION_ID',
        'TIMESTAMP_X',
        'SORT',
        'NAME',
        'ACTIVE',
        'GLOBAL_ACTIVE',
        'PICTURE',
        'DESCRIPTION',
        'DESCRIPTION_TYPE',
        'LEFT_MARGIN',
        'RIGHT_MARGIN',
        'DEPTH_LEVEL',
        'SEARCHABLE_CONTENT',
        'SECTION_PAGE_URL',
        'MODIFIED_BY',
        'DATE_CREATE',
        'CREATED_BY',
        'DETAIL_PICTURE',
    ];

    /**
     * Constructor.
     *
     * @param object $bxObject
     * @param string $modelName
     */
    public function __construct($bxObject, $modelName, $iblockId = null, $collectionClass = null)
    {
        parent::__construct($bxObject, $modelName);

        if ($iblockId)
            $this->iblockId = $iblockId;
        else
            $this->iblockId = $modelName::iblockId();

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    static function getComplexList($keys, $view = null, $modelCollection = null, $preserveEmpty = false)
    {
        $iblockToClass = [];
        $itemToIblock = [];
        $itemsByClass = [];

        $keysIndex = [];


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
            $rs = CIBlockSection::getList([], ['ID' => array_keys($itemToIblock)], false, ['ID', 'IBLOCK_ID']);
            while ($row = $rs->fetch()) {
                $itemToIblock[$row['ID']] = $row['IBLOCK_ID'];
                $iblockToClass[$row['IBLOCK_ID']] = null;
            }
        }


        if ($iblockToClass) {
            foreach ($iblockToClass as $iblockId => $v) {
                $class = Containerable::container()->getIblockService()->getSectionIBlockClassById($iblockId);
                if ($class)
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
                if (!class_exists($cls))
                    continue;
                foreach ($views as $view => $ids) {
                    $elements[$cls][$view] = $cls::query()
                        ->filter(['ID' => array_filter($ids)])
                        ->withView($view)
                        ->getList()
                        ->keyBy('ID');
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


    /**
     * Get the first section with a given code.
     *
     * @param string $code
     *
     * @return SectionModel
     */
    public function getByCode($code)
    {
        $this->filter['CODE'] = $code;

        return $this->first();
    }

    /**
     * Get the first section with a given external id.
     *
     * @param string $id
     *
     * @return SectionModel
     */
    public function getByExternalId($id)
    {
        $this->filter['EXTERNAL_ID'] = $id;

        return $this->first();
    }

    /**
     * Get count of sections that match filter.
     *
     * @return int
     */
    public function count()
    {
        if ($this->queryShouldBeStopped) {
            return 0;
        }

        $queryType = 'SectionQuery::count';
        $filter = $this->normalizeFilter();
        $callback = function () use ($filter) {
            return (int)$this->bxObject->getCount($filter);
        };

        return $this->handleCacheIfNeeded(compact('queryType', 'filter'), $callback);
    }

    /**
     * Setter for countElements.
     *
     * @param $value
     *
     * @return $this
     */
    public function countElements($value)
    {

        $this->countElements = $value;

        return $this;
    }

    /**
     * CIBlockSection::getList substitution.
     *
     * @return Collection
     */
    protected function loadModels($collectionMethod = null)
    {
        $queryType = 'SectionQuery::getList';
        $sort = $this->sort;
        $filter = $this->normalizeFilter();
        $countElements = $this->countElements;
        $select = $this->normalizeSelect();
        $navigation = $this->navigation;
        $keyBy = $this->keyBy;

        $callback = function () use ($sort, $filter, $countElements, $select, $navigation, $collectionMethod) {
            $sections = [];

            if (isset($filter['IBLOCK_ID']) && is_array($filter['IBLOCK_ID'])) {
                $filter['IBLOCK_ID'] = $filter['IBLOCK_ID'][0];
            }

            $rsSections = $this->bxObject->getList($sort, $filter, $countElements, $select, $navigation);

            while ($arSection = $this->performFetchUsingSelectedMethod($rsSections)) {


                // Если передать nPageSize, то Битрикс почему-то перестает десериализовать множественные свойсвта...
                // Проверим это еще раз, и если есть проблемы то пофиксим.
                foreach ($arSection as $field => $value) {
                    if (
                        is_string($value)
                        && \Main\Helper\Str::startsWith($value, 'a:')
                        && (\Main\Helper\Str::startsWith($field, 'UF_') || \Main\Helper\Str::startsWith($field, '~UF_'))
                    ) {
                        $unserializedValue = @unserialize($value);
                        $arSection[$field] = $unserializedValue === false ? $value : $unserializedValue;
                    }
                }

                $modelClass = $this->getModelClass($arSection);

                $this->addItemToResults($sections, new $modelClass($arSection['ID'], $arSection));
            }

            $result = new $this->collectionClass($sections);

            if ($collectionMethod)
                return $result->{$collectionMethod}();
            else
                return $result;

        };

        $cacheParams = compact('queryType', 'sort', 'filter', 'countElements', 'select', 'navigation', 'keyBy', 'collectionMethod');

        return $this->handleCacheIfNeeded($cacheParams, $callback);
    }

    protected function normalizeFilter()
    {
        $this->filter['IBLOCK_ID'] = $this->iblockId;

        return $this->filter;
    }

    protected function normalizeSelect()
    {
        if ($this->fieldsMustBeSelected()) {
            $this->select = array_merge($this->standardFields, $this->select);
        }

        if ($this->propsMustBeSelected()) {
            $this->select[] = 'IBLOCK_ID';
            $this->select[] = 'UF_*';
        }

        $this->select[] = 'ID';

        return $this->clearSelectArray();
    }

    public function getModelClass($arItem = null)
    {
        return $this->modelName;
    }

    public function queryFilterActive(&$result, $condValue, SectionQuery &$query)
    {
        if (is_bool($condValue)) {
            $query->addFilter(['ACTIVE' => $condValue ? 'Y' : 'N']);
        }
    }

    public function getFilters($view = [])
    {
        $filters = [
            'ID' => ['type' => 'number', 'client' => true],
            'ACTIVE' => ['type' => 'boolean', 'client' => true, 'filterFn' => [static::class, 'queryFilterActive']],
            'GLOBAL_ACTIVE' => ['type' => 'boolean', 'client' => true, 'filterFn' => [static::class, 'queryFilterActive']],
            'NAME' => ['type' => 'string', 'client' => true],
            'CODE' => ['type' => 'string', 'client' => true],
            'XML_ID' => ['type' => 'string', 'client' => true],
            'SECTION_ID' => ['type' => 'number', 'client' => true],
            'DEPTH_LEVEL' => ['type' => 'number', 'client' => true],
            'LEFT_BORDER' => ['type' => 'number', 'client' => true],
            'RIGHT_BORDER' => ['type' => 'number', 'client' => true],
            'LEFT_MARGIN' => ['type' => 'number', 'client' => true],
            'RIGHT_MARGIN' => ['type' => 'number', 'client' => true],
            'IBLOCK_ID' => ['type' => 'number', 'client' => true],
            'IBLOCK_ACTIVE' => ['type' => 'boolean', 'client' => true],
            'IBLOCK_NAME' => ['type' => 'string', 'client' => true],
            'IBLOCK_TYPE' => ['type' => 'string', 'client' => true],
            'HAS_ELEMENT' => ['type' => 'number', 'client' => true],
        ];

        $filters += array_map(function ($attr) {
            return [
                    'client' => true,
                ] + $attr;
        }, array_filter($this->getModelAttrsFiltered($view), function ($attr) {
            return $attr['attrType'] === 'prop';
        }));

        return $filters;
    }
}
