<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Query;

class StoreQuery extends D7Query
{
    public $select = ['*', 'UF_*'];

    public function __construct($bxObject, $modelName, $collectionClass = null)
    {
        $this->bxObject = $bxObject;
        $this->modelName = $modelName;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    public function loadCollection($params)
    {
        $rows = [];
        if (!$this->setEmpty) {
            $result = $this->bxObject->getList($params);
            while ($row = $result->fetch()) {
                $rows[$row['ID']] = new $this->modelName($row['ID'], $row);
            }
        }
        return new $this->collectionClass($rows);
    }
}
