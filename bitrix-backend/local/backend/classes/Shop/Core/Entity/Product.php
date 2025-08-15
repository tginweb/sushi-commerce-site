<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\IBlock\ElementQuery;
use Main\Graphql\Types;

class Product extends ElementModel
{
    use Containerable;

    public static function iblockId()
    {
        return static::getIblockService()->getIBlockIdByRole('product');
    }

    public static function getPropsInfo()
    {
        return [
            'CML2_BAR_CODE' => ['view' => true],
            'CML2_BASE_UNIT' => ['view' => true],
            'CML2_ATTRIBUTES' => ['view' => true],
        ];
    }

    public static function getFiltersInfo()
    {
        return parent::getFiltersInfo() + [
                'IN_BESTSELLERS' => [
                    'view' => true,
                    'cfilter' => true,
                    'gqlType' => Types::boolean(),
                    'filterFn' => function (&$result, $condValue, &$query) {
                        $service = static::container()->getSaleService();
                        if ($condValue) {
                            $ids = $service->getBestsellersCached(100);
                            if (empty($ids)) {
                                $query->setEmptyQuery();
                            }
                            $query->filter(['ID' => $ids]);
                        }
                    }
                ],
                'IN_USER_BESTSELLERS' => [
                    'view' => true,
                    'cfilter' => true,
                    'gqlType' => Types::boolean(),
                    'filterFn' => function (&$result, $condValue, &$query) {
                        $service = static::container()->getSaleService();
                        $userId = static::container()->getUserId();
                        if ($condValue) {
                            $ids = $service->getUserBestsellers($userId, 20);
                            if (empty($ids)) {
                                $query->setEmptyQuery();
                            }
                            $query->filter(['ID' => $ids]);
                        }
                    }
                ],
                'IN_FAVORITES' => [
                    'view' => true,
                    'cfilter' => true,
                    'gqlType' => Types::boolean(),
                    'filterFn' => function (&$result, $condValue, ElementQuery &$query) {
                        $service = static::container()->getFavService();
                        if ($condValue) {
                            $ids = $service->getElementsIds();
                            if (empty($ids)) {
                                $query->setEmptyQuery();
                            }
                            $query->filter(['ID' => $ids]);
                        }
                    }
                ],
            ];
    }

    public static function query($iblockId = null)
    {
        return new ProductQuery(static::instantiateObject(), get_called_class(), $iblockId, ProductCollection::class);
    }

    public static function sectionModel()
    {
        return ProductSection::class;
    }

    public function getCatalogPrice()
    {
        $result = $this->getCatalogPrices();

        return count($result) ? reset($result) : null;
    }

    public function getCatalogPrices()
    {
        $result = [];
        $id = 1;

        while (isset($this['CATALOG_PRICE_' . $id])) {
            $result[] = [
                'PRICE' => floatval($this['CATALOG_PRICE_' . $id]),
                'CURRENCY' => $this['CATALOG_CURRENCY_' . $id],
            ];
            $id++;
        }

        return $result;
    }

    public function getCatalogMeasure()
    {
        return [
            'NAME' => 'шт',
            'RATIO' => 1,
        ];
    }

    public function getPropsClientData()
    {
        return parent::getPropsClientData() + [
                'PRICE' => $this->getCatalogFinalPrice(),
                'URL' => $this->getUrl(),
                'IMAGE' => $this->getImageSrc()
            ];
    }

    public function getCatalogFinalPrice()
    {
        $result = $this->getCatalogFinalPrices();

        return count($result) ? reset($result) : null;
    }

    public function getCatalogFinalPrices($cnt = 1, $userGroups = [])
    {
        global $USER;

        $userGroups = !empty($userGroups) ? $userGroups : $USER->GetUserGroupArray();

        if (\CCatalogSku::IsExistOffers($this['ID'])) {


        } else {

            $prices = $this->getCatalogPrices();

            foreach ($prices as $priceId => $price) {

                if ($price['PRICE'] <= 0) continue;

                // это список доступных скидок
                $arDiscounts = \CCatalogDiscount::GetDiscountByProduct(
                    $this['ID'],
                    $userGroups,
                    "N",
                );

                if ($arDiscounts !== false) {
                    $price['DISCOUNTED'] = \CCatalogProduct::CountPriceWithDiscount(
                        $price["PRICE"],
                        $price["CURRENCY"],
                        $arDiscounts
                    );
                } else {
                    $price['DISCOUNTED'] = $price['PRICE'];
                }

                if ($price['DISCOUNTED'] !== $price['PRICE']) {
                    $price['DISCOUNT_PERCENT'] = round((($price['PRICE'] - $price['DISCOUNTED']) / $price['PRICE']) * 100);
                }

                $prices[$priceId] = $price;
            }

        }

        return $prices;
    }

    public function getBasketClientData()
    {
        $result = [
            'ID' => $this['ID'],
            'NAME' => $this['NAME'],
            'URL' => $this->getUrl(),
            'SKU_PROPS' => $this->getSkuPropsValue(),
            'IMAGE' => $this->getImageSrc(),
            'PRICE' => $this->getPriceDiscounted()
        ];

        if ($parent = $this->getData('PARENT')) {
            $result['PARENT'] = $parent->getBasketClientData();
        }

        return $result;
    }

    public function getSkuPropsValue()
    {
        $result = [];

        $propsInfo = static::getPropsInfo();

        foreach ($propsInfo as $propInfo) {
            if ($propInfo['IBLOCK_ID'] == $this['IBLOCK_ID']) {
                if ($propInfo['SKU']) {
                    $propInfo['VALUE'] = $this->getProp($propInfo['CODE']);
                    $result[$propInfo['CODE']] = $propInfo;
                }
            }
        }

        return $result;
    }

    public function getPriceDiscounted()
    {
        $price = $this->getCatalogFinalPrice();
        return $price ? $price['DISCOUNTED'] : 0;
    }

    public function getPrice()
    {
        $price = $this->getCatalogFinalPrice();
        return $price ? $price['PRICE'] : 0;
    }

    public function getSalesCountIsHit()
    {
        return $this->getSalesCount() > $this->container->getConfigService()->get('CATALOG.SALES_COUNT_IS_HIT');
    }

    public function getSalesCountIsPopular()
    {
        return $this->getSalesCount() > $this->container->getConfigService()->get('CATALOG.SALES_COUNT_IS_POPULAR');
    }

    public function getSalesCount()
    {
        $cnt = $this->getData('SALES_COUNT');
        if (isset($cnt)) {
            return $cnt;
        } else {
            $ids = $this->container->getSaleService()->getBestsellersCached(100, 'QUANTITY', [], [], []);
            $cnt = $ids[$this['ID']] ?: 0;
            $this->setData('SALES_COUNT', $cnt);
        }
        return $cnt;
    }

    public function getFlags()
    {
        $items = [];
        return $items;
    }

    public function getGifts()
    {
        $giftsById = $this->container->getCatalogService()->getProductsGifts();
        $items = [];
        $ids = $this->getProp('GIFTS', 'VALUE', true);
        $descriptions = $this->getProp('GIFTS', 'DESCRIPTION', true);
        foreach ($ids as $i => $id) {
            $item = [
                'GIFT_ID' => $id,
                'NAME' => $descriptions[$i],
            ];
            $idCleaned = strtolower(preg_replace('/\s/', '', $id));
            if ($giftsById[$idCleaned]) {
                $item = $item + $giftsById[$idCleaned];
            }
            $items[] = $item;
        }
        return $items;
    }

    public function getAvailableTimeText()
    {
        $availableTimeValues = $this->getProp('AVAILABLE_TIME', 'VALUE', true);
        $availableTimeDays = $this->getProp('AVAILABLE_TIME', 'DESCRIPTION', true);

        $result = [];

        foreach ($availableTimeValues as $i => $availableTimeValue) {
            $result[] = $availableTimeDays[$i] . ': ' . $availableTimeValue;
        }

        return join(', ', $result);
    }

    public function getNotAvaialableReason()
    {
        if (!$this->isAvailableTimeNow()) {
            return [
                'DAY' => 'Пн-Пт',
                'TIME' => '12:00-16:00',
                'TYPE' => 'time',
            ];
        }
    }

    function isSaleSpecial()
    {
        return $this->getProp('SALE_SPECIAL') === 'Y';
    }

    function isSaleAvailable()
    {
        return
            $this['CATALOG_AVAILABLE'] === 'Y' &&
            !$this->isSaleSpecial();
    }

    function isBuild()
    {
        return false;
    }

    function getWeight()
    {
        return $this->getProp('WEIGHT');
    }

    function getBenefits()
    {
        $items = [];
        $values = $this->getProp('BENEFITS', 'VALUE', true);
        if (!empty($values)) {
            foreach ($values as $key => $value) {
                $item = [
                    'PRODUCT_ID' => $value['SUB_VALUES']['BENEFITS_PRODUCT']['~VALUE'],
                    'QUANTITY' => $value['SUB_VALUES']['BENEFITS_QUANTITY']['~VALUE'],
                    'IS_GIFT' => $value['SUB_VALUES']['BENEFITS_IS_GIFT']['~VALUE'],
                ];
                $items[] = $item;
            }
        }
        return $items;
    }

    function getSetItems()
    {
        $items = [];
        $values = $this->getProp('SET_ITEMS', 'VALUE', true);
        if (!empty($values)) {
            foreach ($values as $key => $value) {
                $item = [
                    'PRODUCT_ID' => $value['SUB_VALUES']['SET_ITEMS_PRODUCT_ID']['~VALUE'],
                    'QUANTITY' => $value['SUB_VALUES']['SET_ITEMS_QUANTITY']['~VALUE'],
                ];
                $items[] = $item;
            }
        }
        return $items;
    }
}
