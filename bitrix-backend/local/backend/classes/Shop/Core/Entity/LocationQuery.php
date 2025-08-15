<?php

namespace Shop\Core\Entity;

use Bitrix\Sale\Location;
use Bitrix\Sale\Location\LocationTable;
use Main\Entity\D7\D7Query;
use const TG\Shop\Core\Entity\LANGUAGE_ID;
use const TG\Shop\Core\Entity\SITE_ID;

class LocationQuery extends D7Query
{
    public $select = ['*', 'NAME_LANG' => 'NAME.NAME'];

    public function __construct($modelName, $collectionClass = null)
    {
        $this->modelName = $modelName;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
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

        $keyBy = $this->keyBy;

        $callback = function () use ($params) {
            return $this->loadCollection($params);
        };

        return $this->handleCacheIfNeeded(compact('className', 'params', 'queryType', 'keyBy'), $callback);
    }

    public function loadCollection($params)
    {
        $rows = [];

        if (!$this->setEmpty) {

            $params['filter']['=NAME.LANGUAGE_ID'] = LANGUAGE_ID;

            $result = LocationTable::getList($params);

            while ($row = $result->fetch()) {
                $rows[$row['ID']] = new $this->modelName($row['ID'], $row);
            }
        }

        return new $this->collectionClass($rows);
    }

    public function filterDefault()
    {
        $defaultIds = [];

        $rs = Location\DefaultSiteTable::getList(array(
            'filter' => array(
                'SITE_ID' => SITE_ID,
            ),
            'select' => array(
                'ID' => 'LOCATION.ID',
            )
        ));

        while ($item = $rs->Fetch()) {
            $defaultIds[$item['ID']] = $item['ID'];
        }


        return $this->filter(['ID' => $defaultIds]);
    }
}
