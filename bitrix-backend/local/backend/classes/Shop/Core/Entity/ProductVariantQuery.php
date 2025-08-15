<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementQuery;

class ProductVariantQuery extends ElementQuery
{
    public $select = ['FIELDS'];

    public $priceIds = [];

    public function withView($viewmode = [])
    {
        parent::withView($viewmode);
        return $this->withPriceByUser();
    }

    public function getModelClass($arItem = null)
    {
        if ($this->modelName == Product::class) {
            return static::getIblockService()->getElementIBlockClassById($arItem['IBLOCK_ID'], $this->modelName);
        }
        return $this->modelName;
    }

    public function withPriceByUser()
    {
        return $this->withPrice(\Shop\Core\Helper\Price::getPriceTypes([], true));
    }

    public function withPrice($ids = [])
    {
        foreach ((array)$ids as $id) {
            if (is_array($id))
                $id = $id['ID'];
            $this->priceIds[] = $id;
            $this->select[] = 'CATALOG_GROUP_' . $id;
        }
        return $this;
    }
}
