<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\SectionQuery;

class ProductSectionQuery extends SectionQuery
{
    public function getModelClass($arItem = null)
    {
        if ($this->modelName == ProductSection::class) {
            return static::getIblockService()->getSectionIBlockClassById($arItem['IBLOCK_ID'], $this->modelName);
        }
        return $this->modelName;
    }
}
