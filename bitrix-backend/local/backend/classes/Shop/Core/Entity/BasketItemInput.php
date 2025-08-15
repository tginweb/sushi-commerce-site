<?php

namespace Shop\Core\Entity;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;

class BasketItemInput
{
    public $id;
    public $clientId;
    public $productId;
    public $quantity;
    public $currency;
    public $props = [];
    public $build = [];
    public $buildItems;
    public $fav;
    public $parent;

    function __construct($args = [], $parent = null)
    {
        $this->parent = $parent;

        $args += [
            'id' => null,
            'clientId' => null,
            'productId' => null,
            'currency' => CurrencyManager::getBaseCurrency(),
            'quantity' => 1,
            'props' => [],
            'build' => [],
            'fav' => false,
        ];

        $this->id = intval($args['id']);
        $this->clientId = $args['clientId'];
        $this->productId = intval($args['productId']);
        $this->currency = $args['currency'];
        $this->props = $this->prepareProps($args['props']);
        $this->build = $args['build'];
        $this->fav = $args['fav'];
        $this->quantity = $this->prepareQuantity($args['quantity']);
    }

    function getPropsHash()
    {
        return $this->parent;
    }

    function getParent()
    {
        return $this->parent;
    }

    function getIsFav()
    {
        return $this->getParent() ? $this->getParent()->fav : $this->fav;
    }

    function getProductId()
    {
        return $this->productId;
    }

    function getQuantity()
    {
        return $this->quantity;
    }

    function prepareProps($props)
    {
        $result = [];

        foreach ($props as $propCode => $propVal) {
            if ($propVal) {
                $result[$propCode] = $propVal;
            }
        }

        return $result;
    }

    function prepareQuantity($quantity)
    {
        /*
            $ratio = \CCatalogMeasureRatio::getList([], ['PRODUCT_ID' => $productId]);
            $ratio = $ratio->Fetch();
            $ratio = !empty($ratio) ? Type::toFloat($ratio['RATIO']) : 1;
            $quantity = $quantity < $ratio ? $ratio : $quantity;
            */

        return floatval($quantity);
    }


    function getProductOrFail($productId)
    {
        $arElement = \CIBlockElement::GetByID($productId)->GetNext();

        if (empty($arElement))
            throw new \Exception('Product not found ' . $productId);

        return $arElement;
    }

    function getBuildBasketFields()
    {
        return [
            'NAME' => $this->productId,
            'PRODUCT_ID' => $this->productId,
            'QUANTITY' => $this->quantity,
            'IS_GIFT' => 'N',
        ];
    }

    function createBasketItem($basket, $fields = [])
    {
        $element = $this->getProductOrFail($this->productId);

        $item = $basket->createItem('catalog', $this->productId);

        $item->setFields($fields + [
                'DELAY' => $this->getIsFav() ? 'Y' : 'N',
                'PRODUCT_ID' => $this->productId,
                'QUANTITY' => $this->quantity,
                'CURRENCY' => $this->currency,
                'LID' => Context::getCurrent()->getSite(),
                //'PRODUCT_PROVIDER_CLASS' => class_exists('\Bitrix\Catalog\Product\CatalogProvider') ? '\Bitrix\Catalog\Product\CatalogProvider' : 'CCatalogProductProvider',
                //'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                'PRODUCT_PROVIDER_CLASS' => \Project\Sale\Core\Lib\CatalogProvider\Simple::class,
                'CATALOG_XML_ID' => $element['IBLOCK_EXTERNAL_ID'],
                'PRODUCT_XML_ID' => $element['EXTERNAL_ID']
            ]);

        $item->setFieldNoDemand('QUANTITY', $this->quantity);

        if (!empty($this->clientId))
            $basketProps['CLIENT_ID'] = [
                'NAME' => 'CLIENT_ID',
                'VALUE' => $this->clientId,
                'SORT' => 10
            ];

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

        if (!empty($this->props)) {
            $basketProps['INPUT_PROPS'] = [
                'VALUE' => $this->props,
            ];
        }

        $basketProps['INPUT_PROPS']['VALUE']['BUILD'] = $this->build ?? [];

        $basketProps['INPUT_HASH'] = [
            'VALUE' => $this->getHash(),
        ];

        $buildItems = $this->getBuildItems();

        if (!empty($buildItems)) {
            $basketProps['BUILD'] = [
                'VALUE' => [],
            ];

            foreach ($buildItems as $buildItem) {
                $basketProps['BUILD']['VALUE'][] = $buildItem->getBuildBasketFields();
            }
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

    function getHash()
    {
        $hashProps = [];
        foreach ($this->props as $code => $prop) {
            if ($prop)
                $hashProps[$code] = $prop;
        }
        ksort($hashProps);
        $hashArgs = [$this->productId, $hashProps, $this->getBuildHash()];
        return md5(json_encode($hashArgs));
    }

    function getBuildHash()
    {
        $hashs = [];
        foreach ($this->getBuildItems() as $item) {
            $hashs[] = $item->getHash();
        }
        return !empty($hashs) ? $hashs : md5(json_encode($hashs));
    }

    function getBuildItems()
    {
        if (!isset($this->buildItems)) {
            $this->buildItems = [];
            foreach ($this->build as $item) {
                $this->buildItems[] = new BasketItemInput($item);
            }
        }
        return $this->buildItems;
    }
}
