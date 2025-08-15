<?php

namespace Shop\Core\Entity;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;

class BasketClientItem extends ArrayableModel
{
    public $parent;
    use Containerable;

    public function __construct($fields = [], $parent = null)
    {
        $fields += [
            'CURRENCY' => CurrencyManager::getBaseCurrency(),
        ];
        $this->fields = $fields;
        $this->parent = $parent;
    }

    function getProductOrFail($productId)
    {
        $arElement = \CIBlockElement::GetByID($productId)->GetNext();

        if (empty($arElement))
            throw new \Exception('Product not found');

        return $arElement;
    }

    function getParent()
    {
        return $this->parent;
    }

    function getIsFav()
    {
        return $this->getParent() ? $this->getParent()->fav : $this->fav;
    }

    function getHash()
    {

        if ($this['PRODUCT_ID'])
            return $this['PRODUCT_ID'];

        if ($this['CONSTRUCTOR']) {
            return json_encode($this['CONSTRUCTOR']);
        }
    }

    function createBasketItem($basket, $fields = [])
    {
        if ($this['CONSTRUCTOR']) {
            $element = $this->container->getCatalogService()->ensureConstructorElement([
                'constructor' => $this['CONSTRUCTOR'],
                'sectionCode' => $this['CONSTRUCTOR_SECTION_CODE'],
                'sectionId' => $this['CONSTRUCTOR_SECTION_ID'],
            ]);
            if (!$element)
                return;
        } else {
            $element = $this->getProductOrFail($this['PRODUCT_ID']);
        }

        $item = $basket->createItem('catalog', $element['ID']);

        $item->setFields($fields + [
                'DELAY' => $this->getIsFav() ? 'Y' : 'N',
                'PRODUCT_ID' => $element['ID'],
                'QUANTITY' => $this['QUANTITY'],
                'CURRENCY' => $this['CURRENCY'],
                'LID' => Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => \Project\Sale\Core\Lib\CatalogProvider\Simple::class,
                'CATALOG_XML_ID' => $element['IBLOCK_EXTERNAL_ID'],
                'PRODUCT_XML_ID' => $element['EXTERNAL_ID']
            ]);

        $item->setFieldNoDemand('QUANTITY', $this['QUANTITY']);

        $basketProps = [];

        if (!empty($element['IBLOCK_EXTERNAL_ID']))
            $basketProps['CATALOG.XML_ID'] = [
                'NAME' => 'Catalog XML_ID',
                'VALUE' => $element['IBLOCK_EXTERNAL_ID'],
                'SORT' => 100
            ];

        if (!empty($element['EXTERNAL_ID']))
            $basketProps['PRODUCT.XML_ID'] = [
                'NAME' => 'Product XML_ID',
                'VALUE' => $element['EXTERNAL_ID'],
                'SORT' => 100
            ];

        if (!empty($this['INPUT_PROPS'])) {
            // file_put_contents(__DIR__.'/tttt-111.json', json_encode($this['INPUT_PROPS']));

            $basketProps['INPUT_PROPS'] = [
                'VALUE' => json_encode($this['INPUT_PROPS']),
            ];
        }

        $propsRes = [];
        foreach ($basketProps as $code => $basketProp) {
            $propsRes[] = $basketProp + [
                    'CODE' => $code,
                    'NAME' => $code,
                ];
        }

        $propCollection = $item->getPropertyCollection();

        //$propCollection->redefine($propsRes);
        $propCollection->setProperty($propsRes);

        return $item;
    }
}
