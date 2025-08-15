<?php

namespace Main\Entity\D7;

use Main\DI\Containerable;
use Main\Entity\Query\BaseQuery;

class D7Query extends BaseQuery
{
    const DISCRIMINATOR_FIELD = null;
    /**
     * Query select.
     *
     * @var array
     */
    public $select = ['*'];
    /**
     * Query group by.
     *
     * @var array
     */
    public $group = [];
    /**
     * Query runtime.
     *
     * @var array
     */
    public $runtime = [];
    /**
     * Query limit.
     *
     * @var int|null
     */
    public $limit = null;
    /**
     * Query offset.
     *
     * @var int|null
     */
    public $offset = null;
    /**
     * Cache joins?
     *
     * @var bool
     */
    public $cacheJoins = false;
    /**
     * Data doubling?
     *
     * @var bool
     */
    public $dataDoubling = true;
    var $setEmpty;
    /**
     * Adapter to interact with Bitrix D7 API.
     *
     * @var D7Adapter
     */
    protected $bxObject;

    use Containerable;

    public function count()
    {
        $className = $this->bxObject ? $this->bxObject->getClassName() : static::class;

        $queryType = 'D7Query::count';
        $filter = $this->filter;

        $callback = function () use ($filter) {
            return (int)$this->bxObject->getCount($filter);
        };

        return $this->handleCacheIfNeeded(compact('className', 'filter', 'queryType'), $callback);
    }

    public function setEmpty()
    {

        $this->setEmpty = true;

        return $this;
    }

    public function groupBy($value)
    {
        $this->group = array_merge($this->group, $value);

        return $this;
    }

    public function limit($value)
    {
        $this->limit = $value;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function page($num)
    {
        $num = intval($num) ?: 1;
        return $this->offset((int)$this->limit * ($num - 1));
    }

    public function offset($value)
    {
        $this->offset = $value;

        return $this;
    }

    public function getPage()
    {
        return $this->offset ? round($this->offset / $this->limit) + 1 : 1;
    }

    public function runtime($fields)
    {
        $this->runtime = is_array($fields) ? $fields : [$fields];

        return $this;
    }

    public function cacheJoins($value = true)
    {
        $this->cacheJoins = $value;

        return $this;
    }

    public function enableDataDoubling()
    {
        $this->dataDoubling = true;

        return $this;
    }

    public function disableDataDoubling()
    {
        $this->dataDoubling = false;

        return $this;
    }

    public function setAdapter($bxObject)
    {
        $this->bxObject = $bxObject;

        return $this;
    }

    public function sort($by, $order = 'ASC')
    {
        $this->sort = is_array($by) ? $by : [$by => $order];
        return $this;
    }

    protected function loadModels()
    {
        $params = [
            'select' => $this->select,
            'filter' => $this->filter,
            'group' => $this->group,
            'order' => $this->sort,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'runtime' => $this->runtime,
        ];

        if ($this->cacheTtl && $this->cacheJoins) {
            $params['cache'] = ['ttl' => $this->cacheTtl, 'cache_joins' => true];
        }

        $className = $this->bxObject ? $this->bxObject->getClassName() : static::class;

        $queryType = 'D7Query::getList';
        $keyBy = $this->keyBy;

        $callback = function () use ($className, $params) {
            return $this->loadCollection($params);
        };

        return $this->handleCacheIfNeeded(compact('className', 'params', 'queryType', 'keyBy'), $callback);
    }

    public function loadCollection($params)
    {
        $rows = [];

        if (!$this->setEmpty) {

            $result = $this->bxObject ? $this->bxObject->getList($params) : static::class;

            while ($row = $result->fetch()) {

                $className = $this->getModelClass($row);

                $this->addItemToResults($rows, new $className($row['ID'], $row));
            }
        }

        return new $this->collectionClass($rows);
    }

    public function getModelClass($item = null)
    {
        if ($item && static::DISCRIMINATOR_FIELD) {
            $entityType = strtolower(preg_replace('/Table/', '', $this->bxObject->getClassName()));
            $modelClasses = $this->container->applyFiltersCached('hl-model-class:' . $entityType, []);
            $discriminatorValue = $item[static::DISCRIMINATOR_FIELD];
            if ($discriminatorValue && isset($modelClasses[$discriminatorValue])) {
                return $modelClasses[$discriminatorValue];
            }
        }
        return $this->modelName;
    }


    public function getFilters($view = [])
    {
        $filters = [
            'ID' => ['type' => 'number', 'client' => true],
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
