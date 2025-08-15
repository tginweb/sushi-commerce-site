<?php

namespace Main\Entity\IBlock;

use Bitrix\Iblock;
use Main\Entity\D7\D7Query;

class PropertyQuery extends D7Query
{
    /**
     * Constructor.
     *
     * @param object|string $bxObject
     * @param string $modelName
     */
    public function __construct($modelName, $collectionClass = null)
    {
        $this->modelName = $modelName;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    /**
     * Get list of items.
     *
     * @return PropertyCollection
     */
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

        $queryType = 'IblockProperty.query::getList';
        $keyBy = $this->keyBy;

        $callback = function () use ($params) {
            return $this->loadCollection($params);
        };

        return $this->handleCacheIfNeeded(compact('params', 'queryType', 'keyBy'), $callback);
    }

    public function loadCollection($params)
    {
        $rows = [];

        if (!$this->setEmpty) {
            $items = Iblock\PropertyTable::getList($params)->fetchAll();
            foreach ($items as $item) {
                $cls = $this->getModelClass($item);
                $rows[] = new $cls($item['ID'], $item);
            }
        }

        return new $this->collectionClass($rows);
    }

    public function getModelClass($item = null)
    {
        $propTypes = $this->container->applyFiltersCached('iblock:properties', []);

        $key = $item['IBLOCK_CODE'] . '.' . $item['CODE'];

        $propType = $propTypes[$key] ?? null;

        if ($propType) {
            if ($propType['class']) {
                return $propType['class'];
            }
        }

        return $this->modelName;
    }

}
