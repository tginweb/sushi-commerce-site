<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\FavModel;

class CatalogFavService extends BaseService
{
    function getItemsIds()
    {
        return $this->getElementsIds();
    }

    function getClientFilter()
    {
        $sess = $this->container->getSession();

        $userId = $sess->getUserId();
        $fuserId = $sess->getFuserId();
        $sessionId = $sess->getSessionId();

        $filter = [
            'LOGIC' => 'OR',
        ];

        if ($userId) {
            $filter['UF_USER_ID'] = $userId;
        } else {
            $filter['UF_SESSION_ID'] = $sessionId;
            $filter['UF_FUSER_ID'] = $fuserId;
        }

        return $filter;
    }

    function addManual($productId, $basketItemFields = null, $basketItemProps = null)
    {
        return $this->add('manual', $productId, $basketItemFields, $basketItemProps);
    }

    function addBasketItem($type, $productId, $basketItem = null)
    {
        $basketItemFields = $basketItem->getFavFields();
        $basketItemProps = $basketItem->getFavProps();
    }

    function add($type, $productId, $basketHash, $basketItemFields, $basketItemProps)
    {
        $sess = $this->container->getSession();
        $userId = $sess->getUserId();
        $fuserId = $sess->getFuserId();
        $sessionId = $sess->getSessionId();

        $model = FavModel::query()->filter([
            'UF_ELEMENT_ID' => $productId,
            'UF_TYPE' => $type,
            $this->getClientFilter()
        ])->select(['UF_ELEMENT_ID'])->first();

        if (!$model) {
            $model = new FavModel(null, [
                'UF_TYPE' => $type,
                'UF_ELEMENT_ID' => $productId,
                'UF_IBLOCK_ID' => null,
                'UF_SALES_COUNT' => 0,
                'UF_SESSION_ID' => $sessionId,
                'UF_FUSER_ID' => $fuserId,
                'UF_USER_ID' => $userId,
                'UF_BASKET_HASH' => $basketItemFields,
                'UF_BASKET_ITEM' => $basketItemFields,
                'UF_BASKET_PROPS' => $basketItemProps,
            ]);
            $model->save();
        }

        return $model;
    }

    function remove($productId, $type = 'manual')
    {
        $foundElement = FavModel::query()->filter([
            'UF_ELEMENT_ID' => $productId,
            'UF_TYPE' => $type,
            $this->getClientFilter()
        ])->select(['*'])->first();

        if ($foundElement)
            $foundElement->delete();

        return $this;
    }

    function clear()
    {
        $_SESSION['FAV'] = [];
        return $this;
    }

    function getElementsIds($type = null)
    {
        $filter = [];

        if ($type) {
            $filter['UF_TYPE'] = $type;
        }

        $filter[] = $this->getClientFilter();

        return FavModel::query()->filter($filter)->select(['UF_ELEMENT_ID'])->getList()->pluckArray('UF_ELEMENT_ID');
    }

    function getFavType($type, $field = null)
    {
        $types = [
            'manual' => [
                'name' => 'Вручную'
            ],
            'sale' => [
                'name' => 'Продажи'
            ],
        ];
        return !$field ? ($types[$type] ?? null) : ($types[$type][$field] ?? null);
    }
}



