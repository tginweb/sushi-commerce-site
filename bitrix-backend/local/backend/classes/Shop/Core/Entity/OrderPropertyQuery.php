<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Query;

class OrderPropertyQuery extends D7Query
{
    use Containerable;

    /**
     * Constructor.
     *
     * @param object|string $bxObject
     * @param string $modelName
     */
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

            $propTypes = $this->container->applyFiltersCached('sale:property.types', []);

            while ($row = $result->fetch()) {

                $propType = $propTypes[$row['CODE']];

                $class = $this->modelName;

                if ($propType) {
                    if ($propType['class']) {
                        $class = $propType['class'];
                    }
                }

                $rows[$row['ID']] = new $class($row);
            }
        }

        return new $this->collectionClass($rows);
    }
}
