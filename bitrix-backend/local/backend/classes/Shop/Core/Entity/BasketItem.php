<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketPropertiesCollection;
use Bitrix\Sale\BasketPropertyItem;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\PriceMaths;
use Bitrix\Sale\Registry;
use Main\DI\Containerable;
use Main\Entity\IBlock\ElementQuery;
use function TG\Shop\Core\Entity\htmlspecialcharsbx;

class BasketItem extends Sale\BasketItem
{
    use Containerable;

    public $propsByCode;
    public $related = [];

    static function createFromArray(array $fields = array())
    {
        $registry = Registry::getInstance(static::getRegistryType());
        $orderClassName = $registry->getBasketItemClassName();
        return new $orderClassName($fields);
    }

    static function getClientPropValue($item)
    {
        $giftsById = static::container()->getCatalogService()->getProductsGifts();
        switch ($item['CODE']) {
            case 'GIFT':
                $idCleaned = strtolower(preg_replace('/\s/', '', $item['VALUE']));
                return $giftsById[$idCleaned];
            default:
                return $item['VALUE'];
        }
    }

    public function setPropertyCollectionExt($collection)
    {
        $this->propertyCollection = $collection;
    }

    public static function query()
    {
        return new BasketItemQuery(BasketTable::getEntity());
    }

    public function createFavInputItem($args = [])
    {
        return new BasketItemInput($args + [
                'productId' => $this->getProductId(),
                'props' => $this->getInputProps(),
                'fav' => true
            ]);
    }

    /**
     * @return BasketBase
     */
    public function getBasket()
    {
        /** @var BasketItemCollection $collection */
        $collection = $this->getCollection();

        return $collection->getBasket();
    }

    function getInputProps()
    {
        return $this->getPropValue('INPUT_PROPS') ?? [];
    }

    function getPropValue($name, $def = null)
    {
        $prop = $this->getProp($name);
        return $prop && !empty($prop['VALUE']) ? $prop['VALUE'] : $def;
    }

    function getProp($name, $def = null)
    {
        $this->getPropsByCode();
        return $this->propsByCode[$name] ?? $def;
    }

    function isPriceComputable()
    {
        return $this->isBuild();
    }

    function isBuild()
    {
        return count($this->getBuildItems()) > 0;
    }

    function getBuildItems()
    {
        return $this->getPropValue('BUILD', []);
    }

    function getPriceComputed($base = null)
    {

        global $USER;

        $price = $this->getBasePrice();

        foreach ($this->getBuildItems() as $buildItem) {

            $bundleItemPrice = $buildItem['PRICE'];

            if (!$bundleItemPrice) {
                $arPrice = \CCatalogProduct::GetOptimalPrice($buildItem['PRODUCT_ID'], $buildItem['QUANTITY'], $USER->GetUserGroupArray());
                $bundleItemPrice = $arPrice["PRICE"]["PRICE"];
            }

            $price += $bundleItemPrice;
        }

        return $price;
    }

    function getPropsByCode($refetch = false)
    {
        if (!isset($this->propsByCode) || $refetch) {

            $this->propsByCode = [];

            if (!isset($this->propertyCollection)) {
                $basketPropertyCollection = BasketPropertiesCollection::createBasketPropertiesCollectionObject();
                $basketPropertyCollection->setBasketItem($this);

                $props = $this->getRelated('PROPS') ?? [];

                foreach ($props as $prop) {
                    $basketPropertyItem = BasketPropertyItem::create($basketPropertyCollection);
                    $basketPropertyItem->initFields($prop);
                    $basketPropertyCollection->addItemExt($basketPropertyItem);
                }

                $this->setPropertyCollectionExt($basketPropertyCollection);
            } else {
                $basketPropertyCollection = $this->getPropertyCollection();
            }

            foreach ($basketPropertyCollection as $item) {
                $fields = $item->getFields()->getValues();
                if ($fields['VALUE']) {
                    if ($fields['VALUE'][0] === '[' || $fields['VALUE'][0] === '{') {
                        $fields['VALUE'] = json_decode($fields['VALUE'], true);
                    }
                }
                $this->propsByCode[$item->getField('CODE')] = $fields;
            }
        }
        return $this->propsByCode;
    }

    public function getFinalPriceBase()
    {
        $price = PriceMaths::roundPrecision($this->getBasePrice() * $this->getQuantity());
        return $price;
    }

    function getClientData($arParams = [])
    {
        $product = $this->getProduct();

        $result = [
            'ID' => $this->getId(),
            'NAME' => $this->getField('NAME'),
            'PRODUCT_ID' => $this->getField('PRODUCT_ID'),
            'QUANTITY' => $this->getField('QUANTITY'),
            'PRICE' => $this->getPrice(),
            'FINAL_PRICE' => $this->getFinalPrice(),
        ];

        if ($product) {
            $result['PRODUCT'] = $product->getBasketClientData();
        }

        return $result;
    }

    /**
     * @return Product
     */
    function getProduct()
    {
        return $this->getRelated('PRODUCT');
    }

    function getRelated($name)
    {
        return $this->related[$name];
    }

    function setRelated($name, $value)
    {
        $this->related[$name] = $value;
    }

    function getClientName()
    {
        return $this->getField('NAME');
    }

    function getClientDesc()
    {
        return $this->getPropValue('DESC');
    }

    function isNewItem()
    {
        return $this->isNew() || $this->getRelated('IS_NEW');
    }

    function getHash()
    {
        return $this->getField('PRODUCT_ID') . 'x' . $this->getField('QUANTITY');
    }

    function getInputPropsHash()
    {
        return $this->generatePropsHash($this->getInputProps());
    }

    function generatePropsHash($data)
    {
        $out = [];

        ksort($data);

        foreach ($data as $key => $value) {
            if ($value) {
                if (is_array($value)) {
                    $out[] = $key . '=[' . $this->generatePropsHash($value) . ']';
                } else {
                    $out[] = $key . '=' . $value;
                }
            }
        }

        return join(',', $out);
    }

    function getClientProps()
    {
        $result = [];

        foreach ($this->getPropsByCode() as $prop) {
            $prop['CODE'] = preg_replace('/PROP\./', '', $prop['CODE']);
            $result[] = $prop;
        }

        if ($giftId = $this->getInputProp('GIFT')) {
            $gifts = $this->container->getCatalogService()->getProductsGifts();
            $idCleaned = strtolower(preg_replace('/\s/', '', $giftId));
            if ($gifts[$idCleaned]) {
                $result[] = [
                    'CODE' => 'GIFT',
                    'NAME' => 'Подарок',
                    'VALUE' => $giftId,
                ];
            }
        }

        return $result;
    }

    function getInputHash()
    {
        return $this->getPropValue('INPUT_HASH');
    }

    function getPropsHash()
    {
        return $this->getPropValue('INPUT_PROPS');
    }

    function getFavProps()
    {
        $props = $this->getPropertyCollection()->getPropertyValues();
        $result = [];
        if ($props['PROPS']) {
            return json_decode($props['PROPS']['VALUE'], true);
        }
    }

    function getFavFields()
    {

        $fields = [
            "NAME",
            "PRICE",
            "CUSTOM_PRICE",
        ];

        $result = [];

        foreach ($fields as $field) {
            $result[$field] = $this->getField($field);
        }

        return $result;
    }

    function get1cData()
    {
        $product = $this->getProduct();

        if (!$product)
            return null;

        $result = [
            'Price' => intval($this->getPrice()),
            'Quantity' => intval($this->getQuantity()),
            'GoodsCode' => intval($product->getProp('SERVICE_ID')),
            'Aktsia' => false,
        ];

        if ($product->isBuild()) {

            $constructorType = $product->getConstructorCode();
            $buildItems = $product->getItems();
            $constructorItems = [];

            foreach ($buildItems as $buildItem) {

                $buildItemElement = $buildItem['ELEMENT'];

                $buildItemElementServiceId = $buildItemElement->getProp('SERVICE_ID');

                $itemStr = htmlspecialcharsbx($buildItemElement['NAME'])
                    . ':'
                    . htmlspecialcharsbx(($buildItemElementServiceId))
                    . '='
                    . htmlspecialcharsbx($buildItemElement->getPrice())
                    . '*'
                    . htmlspecialcharsbx($buildItem['QUANTITY']);

                $constructorItems[] = $itemStr;

                if (in_array($buildItemElementServiceId, [43816, 43817, 847254, 847255])) {
                    $result['GoodsCode'] = $buildItemElementServiceId;
                }
            }

            $result['Constructor'] = join(';', $constructorItems);
        }

        $build = $this->getPropValue('BUILD');

        if ($build) {

            $constructorItems = [];

            foreach ($build as $buildItem) {
                $buildItemElement = ElementQuery::getComplexSingle($buildItem['PRODUCT_ID']);

                if (!$buildItemElement)
                    continue;

                $buildItemElementServiceId = $buildItemElement->getProp('SERVICE_ID');

                $itemStr = htmlspecialcharsbx($buildItemElement['NAME'])
                    . ':'
                    . htmlspecialcharsbx(($buildItemElementServiceId))
                    . '='
                    . htmlspecialcharsbx($buildItemElement->getPrice())
                    . '*'
                    . htmlspecialcharsbx($buildItem['QUANTITY']);

                $constructorItems[] = $itemStr;
            }

            $result['Constructor'] = join(';', $constructorItems);
        }

        return $result;
    }

    function isDisable()
    {
        if ($product = $this->getProduct()) {
            return !$product->isAvailableTimeNow();
        }
        //return $this->container->getProjectCatalogService()->checkProductInStopList($this->getProductId());
    }

    function getDisableReason()
    {
        if ($product = $this->getProduct()) {
            if (!$product->isAvailableTimeNow()) {
                return 'время';
            }
        }
    }

    function getBasketHash()
    {
        return $this->getField('PRODUCT_ID');
    }

    function getInputProp($name, $def = null)
    {
        $inputProps = $this->getPropValue('INPUT_PROPS', []);
        return isset($inputProps[$name]) ? $inputProps[$name] : $def;
    }

    function getUuid()
    {
        $uuid = $this->getPropValue('UUID');
        return $uuid ?: md5($this->getId() . '-' . $this->getField('DATE_INSERT')->toString());
    }
}
