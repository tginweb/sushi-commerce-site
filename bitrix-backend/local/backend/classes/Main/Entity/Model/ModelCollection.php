<?php

namespace Main\Entity\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ModelCollection extends Collection
{
    var $context;
    var $relatedLoaded;
    var $cache = [];

    public function getContext()
    {
        return $this->context;
    }

    public function setItemsContext($context)
    {
        foreach ($this->all() as $item) {
            if (method_exists($item, 'setContext'))
                $item->setContext($context);
        }
        return $this;
    }

    public function toArrayValues()
    {
        return array_values($this->toArray());
    }

    function getIds()
    {
        return $this->pluckArray('ID', 'ID');
    }

    public function pluckArray($value, $key = null)
    {
        return Arr::pluck($this->items, $value, $key);
    }

    public function getItemById($id)
    {
        return $this->getById()[$id];
    }

    function getById()
    {
        return $this->pluckArray(null, 'ID');
    }

    public function getItemByCode($code)
    {
        return $this->getByCode()[$code];
    }

    function getByCode()
    {
        return $this->pluckArray(null, 'CODE');
    }

    public function getClientData()
    {
        $result = [];

        foreach ($this->all() as $index => $item) {
            $result[$index] = $item->getClientData();
        }

        return $result;
    }

    public function setItemsCollectionRef()
    {
        foreach ($this->all() as $index => $item) {
            $item->seCollectionRef($this);
        }
    }
}
