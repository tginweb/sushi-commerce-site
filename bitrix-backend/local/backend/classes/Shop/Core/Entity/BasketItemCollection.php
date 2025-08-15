<?php

namespace Shop\Core\Entity;

use Bitrix\Sale\BasketPropertiesCollection;
use Main\DI\Containerable;
use Main\Entity\IBlock\ElementQuery;
use Main\Entity\Model\ModelCollection;

class BasketItemCollection extends ModelCollection
{
    use Containerable;

    public $elementModelClass;
    public $cache = [];
    public $related = [];

    public function __construct($items = [], $elementModelClass = null)
    {
        $this->elementModelClass = $elementModelClass;
        parent::__construct($items);
    }

    public function getProps($refetch = false)
    {
        if (!$this->issetRelated('PROPS') || $refetch) {
            $propsByItem = [];
            $res = BasketPropertiesCollection::getList(array(
                'order' => array(
                    "SORT" => "ASC",
                    "ID" => "ASC"
                ),
                'filter' => array(
                    "BASKET_ID" => $this->getIds(),
                ),
            ));

            while ($property = $res->fetch()) {
                $propsByItem[$property['BASKET_ID']][] = $property;
            }
            $this->setRelated('PROPS', $propsByItem);
        }
        return $this->getRelated('PROPS');
    }

    function getIds()
    {
        return array_map(function ($item) {
            return $item->getId();
        }, $this->all());
    }

    public function getClientData()
    {
        $arItems = [];

        $basketItemsProducts = $this->getProducts()->withParents()->withImages()->withSections();

        $this->setProducts($basketItemsProducts);

        foreach ($this->all() as $basketItem) {
            $arItems[] = $basketItem->getClientData();
        }

        return $arItems;
    }

    public function getProducts($refetch = false)
    {
        if (!$this->issetRelated('PRODUCTS') || $refetch) {
            $this->setRelated('PRODUCTS', array_reduce(ElementQuery::getComplexList($this->getProductIds()), function ($map, $item) {
                $map[$item['ID']] = $item;
                return $map;
            }, []));
        }
        return $this->getRelated('PRODUCTS');
    }

    function issetRelated($name)
    {
        return isset($this->related[$name]);
    }

    public function getProductIds()
    {
        $arProductIds = [];
        foreach ($this->all() as $item) {
            $arProductIds[$item->getField('PRODUCT_ID')] = $item->getField('PRODUCT_ID');
        }
        return $arProductIds;
    }

    function getRelated($name)
    {
        return $this->related[$name];
    }

    function setRelated($name, $value)
    {
        $this->related[$name] = $value;
    }

    public function setItemsProps($props)
    {
        foreach ($this->all() as $item) {
            $item->setRelated('PROPS', $props[$item->getId()] ?? []);
        }
        return $this;
    }

    public function setItemsProducts($products)
    {
        foreach ($this->all() as $item) {
            $item->setRelated('PRODUCT', $products[$item->getField('PRODUCT_ID')]);
        }
        return $this;
    }

    public function withProducts($refetch = false)
    {
        if (!$this->issetRelated('PRODUCTS') || $refetch) {
            $this->setItemsProducts($this->getProducts());
        }
        return $this;
    }

    public function withProps($refetch = false)
    {
        if (!$this->issetRelated('PROPS')) {
            $this->setItemsProps($this->getProps($refetch));
        }
        return $this;
    }

    public function getQuantity()
    {
        $result = 0;
        foreach ($this->all() as $item) {
            $result += $item->getQuantity();
        }
        return $result;
    }

    public function get1cData(OrderModel $order = null)
    {
        $result = [];

        foreach ($this->all() as $item) {

            $itemData = $item->get1cData();

            $result[] = $itemData;

            if ($giftId = $item->getInputProp('GIFT')) {

                $giftProductQuantity = 1;

                list ($giftIdReal, $giftProductQuantityReal) = preg_split('/\s*[xXхХ]\s*/', $giftId);

                if ($giftProductQuantityReal > 0)
                    $giftProductQuantity = $giftProductQuantityReal;

                //$giftResult[$giftProductId] = $giftProductQuantity * $item->getQuantity();

                $result[] = [
                    'Price' => 0,
                    'Quantity' => $giftProductQuantity * $item->getQuantity(),
                    'GoodsCode' => intval($giftIdReal),
                    'Aktsia' => true,
                    'GoodsCodeNabor' => $itemData['GoodsCode'],
                ];
            }
        }


        if ($order && true) {
            if (!$order->isSelfPickup() && $this->container->getDebugService()->isTestRequest()) {
                $price = $this->container->getDeliveryService()->getDeliveryPrice($order->getPropVal('DATE'));
                $result[] = [
                    'Price' => $price,
                    'Quantity' => 1,
                    'GoodsCode' => 7772220,
                ];
            }
        }

        /*
        if (!empty($giftResult)) {
            foreach ($giftResult as $giftProductId => $giftProductQuantity) {
                $result[] = [
                    'Price' => 0,
                    'Quantity' => intval($giftProductQuantity),
                    'GoodsCode' => intval($giftProductId),
                ];
            }
        }
        */

        return $result;
    }

    function getClientItems()
    {
        $result = [];

        foreach ($this->items as $item) {
            if ($item->getField('QUANTITY') > 0) {
                $result[] = $item;
            }
        }
        return $result;
    }
}
