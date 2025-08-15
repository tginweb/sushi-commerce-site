<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\BasketItemInput;
use Shop\Core\Entity\FavBasket;

class FavService extends BaseService
{
    public $basket;

    /**
     * @return FavBasket
     */
    function getBasket($refetch = false)
    {
        if (!isset($this->basket) || $refetch) {
            $this->basket = FavBasket::loadItemsForFUser(
                Bitrix\Sale\Fuser::getId(),
                Bitrix\Main\Context::getCurrent()->getSite()
            );
        }
        return $this->basket;
    }


    function mutationAdd($args)
    {
        $basket = $this->container->getBasketService()->getBasket();
        $favs = $this->getBasket();

        if ($args['basketId']) {
            $basketItem = $basket->getItemById($args['basketId']);
            if ($basketItem) {
                $inputItem = $basketItem->createFavInputItem();
            }

        } else if ($args['productId']) {
            $inputItem = new BasketItemInput($args + ['fav' => true]);
        }

        if (!$inputItem)
            return null;

        foreach ($favs as $item) {
            if ($item->getField('PRODUCT_ID') == $inputItem->getProductId()) {
                $foundItem = $item;
            }
        }

        if ($foundItem) {
            return $foundItem;
        } else {
            $item = $inputItem->createBasketItem($favs, []);
            $favs->save();
            return $item;
        }
    }

    function mutationRemove($args)
    {
        $basket = $this->getBasket();

        foreach ($basket as $item) {
            if ($args['productId']) {
                if ($item->getField('PRODUCT_ID') == $args['productId']) {
                    $foundItem = $item;
                }
            } else if ($args['itemId']) {
                if ($item->getId() == $args['itemId']) {
                    $foundItem = $item;
                }
            }
        }

        if ($foundItem) {
            $foundItem->delete();
            $basket->save();
            return $foundItem->getId();
        }
    }

    function mutationClear()
    {
        $basket = $this->getBasket();
        foreach ($basket as $item) {
            $item->delete();
        }
        $basket->save();
    }

    function getElementsIds() {
        $basket = $this->getBasket();
        $res = [];
        foreach ($basket as $item) {
            $res[] = $item->getField('PRODUCT_ID');
        }
        return $res;
    }
}



