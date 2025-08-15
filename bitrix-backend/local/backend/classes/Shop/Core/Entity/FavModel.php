<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Model;

class FavModel extends D7Model
{
    public $jsonFields = [
        'UF_BASKET_ITEM' => [],
        'UF_BASKET_PROPS' => [],
    ];

    function onBeforeSave()
    {
        foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
            if (is_array($this[$jsonField]) || empty($this[$jsonField])) {
                $this[$jsonField] = json_encode($this[$jsonField] ?? ['hold' => true]);
            }
        }
    }

    function onAfterSave()
    {
        foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
            $this[$jsonField] = json_decode($this[$jsonField], true) ?? [];
        }
    }

    function afterFill()
    {
        if (!$this['ID']) return;
        foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
            $this[$jsonField] = json_decode($this[$jsonField], true) ?? [];
        }
    }

    public static function tableName()
    {
        return 'catalog_fav';
    }

    public function getBasketItemFields()
    {
        return $this['UF_BASKET_ITEM'] ?? [];
    }

    public function getBasketItemProps()
    {
        return $this['UF_BASKET_PROPS'] ?? [];
    }

    public function getVitrualBasketItem()
    {

        $fields = [
                'NAME' => $this['UF_NAME'],
                'PRODUCT_ID' => $this['UF_ELEMENT_ID'],
            ] + $this->getBasketItemFields();


        $item = BasketItem::createFromArray($fields);

        $collection = $item->getPropertyCollection();

        $collection->setProperty($this->getBasketItemProps());

        return $item;
    }
}
