<?php

namespace Main\Dataloader;

use Main\Entity\IBlock\ElementQuery;
use Main\Registry;

class ElementLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('element', $this);
    }

    function batch($keys)
    {
        list($keys, $field, $view) = $this->extractKeys($keys);
        $items = ElementQuery::getComplexList($keys, $view, null, true);
        if ($field) {
            $items = array_column($items, $field);
        }
        return $this->getPromiseAdapter()->createAll($items);
    }
}




