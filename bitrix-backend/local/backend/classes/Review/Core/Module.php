<?php

namespace Review\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;
use Review\Core\Service\ReviewService;
use function TG\Review\Core\AddEventHandler;

class Module extends BaseModule
{

    function register($scopes = [])
    {
        parent::register($scopes);

        $container = $this->container;

        $container->define(ReviewService::class);

        AddEventHandler("iblock", "OnAfterIBlockElementUpdate", function ($arFields) {
            $this->getService()->OnAfterIBlockElementUpdate($arFields);
        });

        AddEventHandler("iblock", "OnBeforeIBlockElementDelete", function ($arFields) {
            $this->getService()->OnBeforeIBlockElementDelete($arFields);
        });

        $this->container->addFilter('review:entity_types', [$this, 'reviewEntityTypes']);
        $this->container->addAction('main:cache-clear', [$this, 'onCacheClear']);
    }

    function getService()
    {
        return $this->container->getReviewService();
    }

    function onCacheClear($arg = [])
    {
        if ($arg['recalc']) {

            $service = $this->getService();

            $service->recalcProductsRating();

            //$this->container->getRev
        }
    }

    function registerTypes()
    {
        Types::types([
            'Review' => \Review\Core\Graphql\ReviewType::class,
        ]);
    }

    function reviewEntityTypes($data)
    {
        $data['product'] = [
            'NAME' => 'Отзыв на товар'
        ];
        $data['order'] = [
            'NAME' => 'Отзыв на заказ'
        ];
        $data['element'] = [
            'NAME' => 'Отзыв на элемент'
        ];
        $data['social'] = [
            'NAME' => 'Отзыв из соц. сетей'
        ];
        return $data;
    }

    function registerApiResolvers()
    {
        new \Review\Core\Api\Controller();
    }
}
