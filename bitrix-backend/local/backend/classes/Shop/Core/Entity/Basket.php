<?php

namespace Shop\Core\Entity;

use Bitrix\Main;
use Bitrix\Sale;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\PriceMaths;
use Bitrix\Sale\Registry;
use Main\DI\Containerable;

class Basket extends Sale\Basket
{
    use Containerable;

    static $isLoadForFUserIdExt = false;
    public $extCollectionInstance = null;

    /**
     * @param $fUserId
     * @param $siteId
     * @return BasketBase
     * @throws Main\ArgumentException
     * @throws Main\ArgumentTypeException
     * @throws Main\NotImplementedException
     */
    public static function loadItemsForFUser($fUserId, $siteId)
    {
        static::$isLoadForFUserIdExt = true;
        return parent::loadItemsForFUser($fUserId, $siteId);
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws Main\NotImplementedException
     */
    public static function getList(array $parameters = array())
    {
        $parameters['filter']['DELAY'] = 'N';
        return parent::getList($parameters);
    }

    /**
     * @return BasketBase
     * @throws Main\ArgumentException
     * @throws Main\NotImplementedException
     */
    public static function createObject()
    {
        $registry = Registry::getInstance(static::getRegistryType());
        $basketClassName = $registry->getBasketClassName();

        return new $basketClassName;
    }

    static function loadFromDbExt(array $filter)
    {
        return parent::loadFromDb($filter);
    }

    public function getClientData($arParams = [])
    {
        $basketItems = $this->getBasketItemsCollectionInstance();
        return [
            'PRICE' => $this->getPrice(),
            'COUNT' => $basketItems->count(),
            'QUANTITY' => $basketItems->getQuantity(),
            'ITEMS' => $basketItems->getClientData(),
        ];
    }

    public function itemsLoadAll($refetch = false)
    {
        $this->getBasketItemsCollectionInstance($refetch)->withProducts($refetch)->withProps($refetch);
        return $this;
    }

    /**
     * @return BasketItemCollection
     */
    public function getBasketItemsCollectionInstance($reload = false)
    {
        if (!isset($this->extCollectionInstance) || $reload) {
            $this->extCollectionInstance = $this->getBasketItemsCollection();
        }
        return $this->extCollectionInstance;
    }

    public function getBasketItemsCollection()
    {
        return new BasketItemCollection($this->collection, $this->container->getCatalogProductElementModelClass());
    }

    public function deleteUnorderable()
    {
        foreach ($this->getBasketItems() as $item) {
            if (!$item->canBuy()) {
                $item->delete();
            }
        }
    }


    public function getBasketItemByProductId($module, $id)
    {
        /** @var \Shop\Core\Entity\BasketItem $item */
        foreach ($this as $item)
            if ($item->getField('MODULE') == $module && $item->getProductId() == $id)
                return $item;

        return null;
    }

    function getRecomends($query = null, $limit, $getParentOnly = false)
    {

        if (!$query) {
            $query = $this->container->getCatalogProductElementModelClass()::query()->withViewPublic();
        }

        $productIds = $this->getBasketItemsCollection()->getProductIds();

        $recProductIds = [];

        foreach ($productIds as $productId) {
            $itemLimit = round($limit / count($productIds)) ?: 1;
            $recProductIds = array_merge($recProductIds, $this->getRecomendIds($productId, 1, $itemLimit, $getParentOnly));
        }

        if (count($recProductIds) < $limit) {

            $arFilter = [];

            $rs = \CSaleProduct::GetBestSellerList('AMOUNT', $arFilter, [], $limit - count($recProductIds));

            while ($bitem = $rs->fetch()) {
                $recProductIds[] = $bitem['PRODUCT_ID'];
            }
        }

        if (!empty($recProductIds)) {
            return $query->filter(['ID' => $recProductIds])->getList()->withImages();
        } else {
            return new $query->getCollectionClass();
        }
    }

    function getRecomendIds($ID, $minCNT, $limit, $getParentOnly = false)
    {
        global $DB;

        $limit = (int)$limit;
        if ($limit < 0)
            $limit = 0;
        $minCNT = (int)$minCNT;
        if ($minCNT < 0)
            $minCNT = 0;

        $getParentOnly = ($getParentOnly === true);

        $elementInclude = [$ID];
        $elementExclude = $this->getBasketItemsCollectionInstance()->getProductIds();

        if ($getParentOnly) {
            $strSql = "select PARENT_PRODUCT_ID from b_sale_product2product where PRODUCT_ID IN (" . implode(',', $elementInclude) . ")";
            if (!empty($elementExclude))
                $strSql .= " and PARENT_PRODUCT_ID not in (" . implode(',', $elementExclude) . ")";
            if ($minCNT > 0)
                $strSql .= " and CNT >= " . $minCNT;
            $strSql .= ' group by PARENT_PRODUCT_ID';
            if ($limit > 0)
                $strSql .= " limit " . $limit;
        } else {
            $strSql = "select * from b_sale_product2product where PRODUCT_ID in (" . implode(',', $elementInclude) . ")";
            if (!empty($elementExclude))
                $strSql .= " and PARENT_PRODUCT_ID not in (" . implode(',', $elementExclude) . ")";
            if ($minCNT > 0)
                $strSql .= " and CNT >= " . $minCNT;
            $strSql .= " order by CNT desc, PRODUCT_ID asc";
            if ($limit > 0)
                $strSql .= " limit " . $limit;
        }


        $rs = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

        $productIds = [];

        while ($product = $rs->fetch()) {
            $productIds[] = $product['PARENT_PRODUCT_ID'];
        }

        return $productIds;
    }

    public function setOrderEmpty()
    {
        $this->order = null;
    }

    public function getMinPrice()
    {
        return $this->container->getConfigService()->get('SALE.MIN_ORDER_PRICE');
    }

    public function isMinPriceReached()
    {
        return $this->container->getConfigService()->get('SALE.MIN_ORDER_PRICE') <= $this->getPrice();
    }

    /**
     * @return BasketBase
     */
    public function getBasket()
    {
        return $this;
    }

    public function getGiftOffersItems()
    {
        \Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();

        $giftManager = \Bitrix\Sale\Discount\Gift\Manager::getInstance();

        if ($userId = \Bitrix\Main\Engine\CurrentUser::get()->getId())
            $giftManager->setUserId($userId);

        $collections = $giftManager->getCollectionsByBasket($this);

        \Bitrix\Sale\Compatible\DiscountCompatibility::revertUsageCompatible();

        $res = [];

        foreach ($collections as $collection) {
            foreach ($collection as $gift) {
                $res[] = $gift->getProductId();
            }
        }

        return $res;
    }

    public function getClientOffers()
    {
        $offersSpecial = [];
        $offersRecommendation = [];

        $orderPrice = $this->getOrder()->getPriceTotal();

        if ($orderPrice > 1200) {
            $offersSpecial[] = [
                'ELEMENT_ID' => 876758,
                'MODE' => 'one'
            ];
        }

        $allOffers = array_merge($offersSpecial, $offersRecommendation);

        $basketItems = $this->getBasketItemsCollectionInstance();
        $basketItemsProductIds = $basketItems->getProductIds();

        $result = [];

        foreach ($allOffers as $offer) {
            if (!isset($basketItemsProductIds[$offer['ELEMENT_ID']])) {
                $result[] = $offer;
            }
        }

        /*
        $offersSpecial = $_SESSION['BASKET_SPECIAL_OFFERS'] ?? [];
        $offersSpecial = array_map(function ($item) {
            return $item + [
                'TYPE' => 'special'
            ];
        }, $offersSpecial);

        $offersRecommendation = $_SESSION['BASKET_RECOMMENDATION_OFFERS'] ?? [];
        $offersRecommendation = array_map(function ($item) {
            return $item + [
                    'TYPE' => 'recommendation'
                ];
        }, $offersRecommendation);


        $allOffers = array_merge($offersSpecial, $offersRecommendation);

        $basketItems = $this->getBasketItemsCollectionInstance();
        $basketItemsProductIds = $basketItems->getProductIds();

        $result = [];

        foreach ($allOffers as $offer) {
            if (!isset($basketItemsProductIds[$offer['ELEMENT_ID']])) {
                $result[] = $offer;
            }
        }
        */

        return $result;
    }

    /**
     * Getting basket price without discounts
     *
     * @return float
     */
    public function getBasePriceWithoutGifts()
    {
        $orderPrice = 0;

        /** @var \Shop\Core\Entity\BasketItem $basketItem */
        foreach ($this->collection as $basketItem) {
            if (!$basketItem->getBasePrice())
                continue;

            if (!$basketItem->isBundleChild())
                $orderPrice += PriceMaths::roundPrecision($basketItem->getBasePrice() * $basketItem->getQuantity());
            //$orderPrice += PriceMaths::roundPrecision($basketItem->getPrice() * $basketItem->getQuantity());
        }

        $orderPrice = PriceMaths::roundPrecision($orderPrice);

        return $orderPrice;
    }

    public function getHash()
    {
        $itemsHashs = [];
        $items = $this->getBasketItemsCollectionInstance();

        foreach ($items as $item) {
            $itemsHashs[] = $item->getHash();
        }

        return join('-', $itemsHashs);
    }
}
