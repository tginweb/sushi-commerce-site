<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class BasketClientItemCollection extends ModelCollection
{
    public function __construct($items = [], $elementModelClass = null)
    {
        parent::__construct($items);
    }

    public function itemsByHash()
    {

        $result = [];

        foreach ($this->all() as $item) {
            $result[$item->getHash()] = $item;
        }

        return $result;
    }

    static function createFromRows($rows)
    {
        $items = array_map(function ($row) {
            return new BasketClientItem($row);
        }, $rows);
        return new self($items);
    }
}
