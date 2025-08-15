<?php

namespace Main\Dataloader;

use Main\Entity\IBlock\SectionQuery;
use Main\Registry;
use Overblog\DataLoader\DataLoader;

class SectionLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('section', $this);
    }

    function createLoader()
    {
        return new DataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {
        list($keys) = $this->extractKeys($keys);

        $items = SectionQuery::getComplexList($keys, $view, null, true);

        if ($field) {
            $items = array_column($items, $field);
        }

        return $this->getPromiseAdapter()->createAll($items);
    }


}




