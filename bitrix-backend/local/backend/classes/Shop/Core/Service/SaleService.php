<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Entity\IBlock\ElementQuery;
use Main\Service\BaseService;

class SaleService extends BaseService
{
    public $productsCached = [];

    function getSaleProvidersInfo()
    {
        return $this->container->applyFiltersCached('sale.providers', []);
    }

    function getSaleProviderInfo($name)
    {
        $providers = $this->getSaleProvidersInfo();
        return $providers[$name];
    }

    function getSaleProvider($name)
    {
        if ($info = $this->getSaleProviderInfo($name)) {
            return $info['CLASS']::instance();
        }
    }

    function getProductSaleProvider($element)
    {
        if (is_object($element)) {
            $providerName = $element['PROPERTY_SALE_PROVIDER_VALUE'];
        } else if (is_scalar($element)) {
            $elementId = intval($element);
            if ($elementId) {
                $element = $this->getProductCached($elementId);
                if ($element)
                    $providerName = $element['PROPERTY_SALE_PROVIDER_VALUE'];
            }
        }

        if (!$providerName) {
            $providerName = 'product';
        }

        if ($providerName) {
            return $this->getSaleProvider($providerName);
        }
    }

    function getProductCached($elementId)
    {
        $elementId = intval($elementId);

        if ($elementId <= 0)
            return null;

        if (!isset($this->productsCached[$elementId])) {
            $this->productsCached[$elementId] = ElementQuery::getComplexSingle($elementId, 'public');
        }

        return $this->productsCached[$elementId];
    }

    function getUserBestsellers($userId, $limit, $by = 'QUANTITY', $arFilter = [], $arOrderFilter = [])
    {
        $arOrderFilter['USER_ID'] = $userId;

        $rs = \CSaleProduct::GetBestSellerList($by, $arFilter, $arOrderFilter, $limit);

        $ids = [];

        while ($item = $rs->fetch()) {
            $ids[] = $item['PRODUCT_ID'];
        }

        return $ids;
    }

    function getBestsellersCached($limit, $by = 'QUANTITY', $arBasketFilter = [], $arOrderFilter = [], $arElementFilter = [])
    {
        return $this->container->getCacheService()->getCachedCallback(__FUNCTION__, [$this, 'getBestsellers'], func_get_args(), 3600 * 24 * 30);
    }

    function getBestsellers($limit, $by = 'QUANTITY', $arBasketFilter = [], $arOrderFilter = [], $arElementFilter = [])
    {
        $dateTo = new \DateTime();
        $dateTo->setDate(2022, 01, 01);
        $arOrderFilter['>DATE_INSERT'] = $dateTo->format('d.m.Y');
        $rs = \CSaleProduct::GetBestSellerList($by, $arBasketFilter, $arOrderFilter, $arElementFilter, $limit);
        $ids = [];
        while ($item = $rs->fetch()) {
            $ids[$item['PRODUCT_ID']] = $item['QUANTITY'];
        }
        return $ids;
    }

}



