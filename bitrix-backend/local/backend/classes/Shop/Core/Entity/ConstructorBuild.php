<?php

namespace Shop\Core\Entity;

class ConstructorBuild extends Product
{
    public $items;
    public $sostav;

    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product_constructor_build');
    }

    static function OnSaleBasketBeforeSavedItem($event)
    {
        $product = $event->getParameter('PRODUCT');
        $basketItem = $event->getParameter('ITEM');
        $basketItemProps = $event->getParameter('PROPS');

        if (!$basketItem->isNewItem())
            return;

        /*
        $basketItem->setFieldNoDemand('CUSTOM_PRICE', 'Y');
        $basketItem->setFieldNoDemand('PRICE', $product->getSostavPrice());
        $basketItem->setFieldNoDemand('BASE_PRICE', $product->getSostavPrice());
        */

        $basketItem->setFieldNoDemand('NAME', 'WOK: ' . $product->getPrimaryTitle());

        $basketItemProps['PROP.FILLING_NAME'] = [
            'NAME' => 'Состав',
            'VALUE' => $product->getFillingTitle()
        ];
    }

    function saveCatalogProduct()
    {
        $arFields = array(
            'WIDTH' => 1,
        );

        $productRs = \CCatalogProduct::getList([], ['ID' => $this['ID']], false, false, ['ID']);
        $product = $productRs->Fetch();

        if (empty($product)) {
            $arFields['ID'] = $this['ID'];
            \CCatalogProduct::Add($arFields, false);
        } else {
            \CCatalogProduct::Update($this['ID'], $arFields);
        }

        $price = $this->getSostavPrice();

        $result = \CPrice::SetBasePrice($this['ID'], $price, 'RUB');
    }

    function getSostavPrice()
    {
        $result = 0;
        foreach ($this->getItems() as $item) {
            $result += $item['ELEMENT']->getPrice() * $item['QUANTITY'];
        }
        return $result;
    }

    function getItems()
    {
        if (!isset($this->items)) {
            $result = [];

            $sostavElementIds = [];

            $stostav = $this->getSostav();

            foreach ($stostav as $stostavItem) {
                $sostavElementIds[] = $stostavItem['ELEMENT_ID'];
            }

            $elements = Constructor::query()->withViewList()->filter(['ID' => $sostavElementIds])->getList();

            foreach ($stostav as $stostavItem) {
                $elementId = $stostavItem['ELEMENT_ID'];
                if ($elements[$elementId]) {
                    $result[$elementId] = [
                        'ID' => $elementId,
                        'QUANTITY' => $stostavItem['QUANTITY'],
                        'ELEMENT' => $elements[$elementId]
                    ];
                }
            }
            $this->items = $result;
        }
        return $this->items;
    }

    function getSostav()
    {
        if (!isset($this->sostav)) {

            $this->sostav = [];

            $ids = $this->getProp('SOSTAV', 'VALUE', true);
            $quantities = $this->getProp('SOSTAV', 'DESCRIPTION', true);

            foreach ($ids as $i => $id) {
                $this->sostav[] = [
                    'ELEMENT_ID' => $id,
                    'QUANTITY' => intval(unserialize($quantities[$i])),
                ];
            }
        }
        return $this->sostav;
    }

    function setSostav($data)
    {
        $this->sostav = $data;
        //$this->setPropValue('SOSTAV', $data);
    }

    function getWeight()
    {

        $result = 0;
        foreach ($this->getItems() as $item) {
            $result += $item['ELEMENT']->getWeight() * $item['QUANTITY'];
        }
        return $result;
    }

    function sostavToProps()
    {
        $items = [];

        foreach ($this->getSostav() as $item) {
            $items[] = [
                'VALUE' => $item['ELEMENT_ID'],
                'DESCRIPTION' => $item['QUANTITY'],
            ];
        }

        $this->setPropValue('SOSTAV', $items);

        return $this;
    }

    function getSostavHash()
    {

        $items = $this->getSostav();
        $map = [];
        foreach ($items as $item) {
            $map[$item['ELEMENT_ID']] = $item['QUANTITY'];
        }

        ksort($map);

        foreach ($map as $elementId => $elementQuantity) {
            $parts[] = $elementId . ':' . $elementQuantity;
        }

        return join('-', $parts);
    }

    function getDesc()
    {
        $result = '';

        if ($primary = $this->getPrimaryItem()) {

            $result = $primary['ELEMENT']['NAME'] . ' ';
        }

        $secondaryTitles = [];

        $secondaryTitles[] = $this->getSectionItemsTitle('с ', 15);
        $secondaryTitles[] = $this->getSectionItemsTitle('с ', 16);
        $secondaryTitles[] = $this->getSectionItemsTitle('в ', 17);

        $result .= join(', ', array_filter($secondaryTitles));

        return $result;
    }

    function getPrimaryItem()
    {
        foreach ($this->getItems() as $item) {
            if ($item['ELEMENT']['IBLOCK_SECTION_ID'] == 14) {
                return $item;
            }
        }
    }

    function getSectionItemsTitle($sectionId = null)
    {

        $result = [];

        foreach ($this->getItems() as $item) {
            if (!$sectionId || $item['ELEMENT']['IBLOCK_SECTION_ID'] == $sectionId) {

                if (in_array($item['ELEMENT']['IBLOCK_SECTION_ID'], [14])) {
                    continue;
                }

                //$itemTitle = strtolower($item['ELEMENT']['PROPERTY_SUMMARY_LABEL_VALUE'] ?: $item['ELEMENT']['NAME']);
                $itemTitle = $item['ELEMENT']['NAME'];
                if ($item['QUANTITY'] > 1) $itemTitle .= ' x' . $item['QUANTITY'];
                $result[] = $itemTitle;
            }
        }

        return !empty($result) ? join(', ', $result) : '';
    }

    function getCatalogFinalPrice()
    {
        $price = $this->getSostavPrice();
        return [
            'DISCOUNTED' => $price,
            'PRICE' => $price
        ];
    }

    function getBasketTitle()
    {
        return 'WOK: ' . join('#', [$this->getPrimaryTitle(), $this->getFillingTitle()]);
    }

    function getPrimaryTitle()
    {
        if ($primary = $this->getPrimaryItem()) {
            return $primary['ELEMENT']['NAME'];
        }
    }

    function getFillingTitle()
    {
        return $this->getSectionItemsTitle();
    }

    function getConstructorUrl()
    {
        return '/catalog/constructor/' . $this->getConstructorCode();
    }

    function getConstructorCode()
    {
        return $this->getProp('CONSTRUCTOR', 'XML_ID');
    }

    function isBuild()
    {
        return true;
    }

    function getName()
    {
        return $this->getPrimaryTitle();
    }
}
