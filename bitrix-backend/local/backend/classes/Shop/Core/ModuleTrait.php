<?php

namespace Shop\Core;

trait ModuleTrait
{
    function getVorderClass()
    {
        return $this->val('vorder.class', \Shop\Core\Entity\VorderCurrent::class);
    }

    /**
     * @return \Shop\Core\Service\CatalogFavService
     */
    function getCatalogFavService()
    {
        return $this->get(\Shop\Core\Service\CatalogFavService::class);
    }

    /**
     * @return \Shop\Core\Service\CatalogService
     */
    function getCatalogService()
    {
        return $this->get(\Shop\Core\Service\CatalogService::class);
    }


    /**
     * @return \Shop\Core\Service\PaymentService
     */
    function getPaymentService()
    {
        return $this->get(\Shop\Core\Service\PaymentService::class);
    }

    /**
     * @return \Shop\Core\Service\DeliveryService
     */
    function getDeliveryService()
    {
        return $this->get(\Shop\Core\Service\DeliveryService::class);
    }

    /**
     * @return \Shop\Core\Service\CouponService
     */
    function getCouponService()
    {
        return $this->get(\Shop\Core\Service\CouponService::class);
    }


    /**
     * @return \Shop\Core\Service\BenefitService
     */
    function getBenefitService()
    {
        return $this->get(\Shop\Core\Service\BenefitService::class);
    }

    /**
     * @return \Shop\Core\Service\DiscountService
     */
    function getDiscountService()
    {
        return $this->get(\Shop\Core\Service\DiscountService::class);
    }

    /**
     * @return \Shop\Core\Service\SaleService
     */
    function getSaleService()
    {
        return $this->get(\Shop\Core\Service\SaleService::class);
    }

    /**
     * @return \Shop\Core\Service\ClientCardService
     */
    function getSaleClientCardService()
    {
        return $this->get(\Shop\Core\Service\ClientCardService::class);
    }

    /**
     * @return \Shop\Core\Service\OrderHistoryService
     */
    function getOrderHistoryService()
    {
        return $this->get(\Shop\Core\Service\OrderHistoryService::class);
    }

    /**
     * @return \Shop\Core\Service\OrderService
     */
    function getOrderService()
    {
        return $this->get(\Shop\Core\Service\OrderService::class);
    }

    /**
     * @return \Shop\Core\Service\OrderAttributesService
     */
    function getOrderAttributesService()
    {
        return $this->get(\Shop\Core\Service\OrderAttributesService::class);
    }

    /**
     * @return \Shop\Core\Service\OrderProfileService
     */
    function getOrderProfileService()
    {
        return $this->get(\Shop\Core\Service\OrderProfileService::class);
    }

    /**
     * @return \Shop\Core\Service\BasketService
     */
    function getBasketService()
    {
        return $this->get(\Shop\Core\Service\BasketService::class);
    }

    /**
     * @return \Shop\Core\Service\FavService
     */
    function getFavService()
    {
        return $this->get(\Shop\Core\Service\FavService::class);
    }

    /**
     * @return \Shop\Core\Entity\Basket
     */
    function getBasket()
    {
        return $this->get(Module::DI_BASKET_MODEL);
    }

    /**
     * @return \Shop\Core\Entity\VorderCurrent
     */
    function getVorder()
    {
        return $this->get(Module::DI_VORDER_MODEL);
    }


    /**
     * @return \Shop\Core\Service\LocationService
     */
    function getLocationService()
    {
        return $this->get(\Shop\Core\Service\LocationService::class);
    }
}

