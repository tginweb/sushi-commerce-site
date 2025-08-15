<?php

namespace Shop\Core\Service;

use Bitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main;
use Bitrix\Sale;
use Main\Error\UserError;
use Main\Service\BaseService;
use Shop\Core\Entity\Basket;
use Shop\Core\Entity\BasketClientItemCollection;
use Shop\Core\Entity\BasketItem;
use Shop\Core\Entity\BasketItemInput;
use Shop\Core\Entity\BasketItemProps;

class BasketService extends BaseService
{
    /** @var Basket $basket */
    public $basket;

    public $favBasket;
    public $saleEventContextName = null;

    public $specialOffers = [];

    function setEventContextName($name)
    {
        $this->saleEventContextName = $name;
    }

    function getEventContextName()
    {
        return $this->saleEventContextName;
    }

    function onEvent(Main\Event $event, $orderSave = false)
    {

        $basket = $event->getParameter('ENTITY');

        $contextName = $this->getEventContextName();

        $event->setParameter('CONTEXT_NAME', $contextName);

        foreach ($basket as $basketItem) {

            $productId = $basketItem->getField('PRODUCT_ID');

            if ($basketItem->isPriceComputable()) {
                $price = $basketItem->getPriceComputed();
                $basketItem->setField('CUSTOM_PRICE', 'Y');
                $basketItem->setField('PRICE', $price);
                $basketItem->setField('BASE_PRICE', $price);
            }

            $product = $this->container->getSaleService()->getProductCached($productId);

            if ($product) {

                $basketItemPropsCollection = $basketItem->getPropertyCollection();

                $basketItemProps = new BasketItemProps();
                $basketItemProps->setPropertyCollection($basketItem->getPropertyCollection());

                $event->setParameters([
                    'PRODUCT' => $product,
                    'ITEM' => $basketItem,
                    'PROPS' => $basketItemProps,
                    'CONTEXT_NAME' => $contextName
                ]);

                $provider = $this->container->getSaleService()->getProductSaleProvider($product);

                if (!$provider) {
                    throw new \Exception('Product sale provider not found');
                }

                $result = $provider->mixinsEvent($event->getEventType() . 'Item', $event);

                if ($basketItemProps->isChanged()) {
                    $basketItemPropsCollection->setProperty($basketItemProps->forBasketItemCollection());
                    $basketItem->getPropsByCode(true);
                }

                foreach ($basketItemPropsCollection as $prop) {
                    $propVal = $prop->getField('VALUE');
                    if (is_array($propVal)) {
                        $prop->setField('VALUE', json_encode($propVal));
                    }
                }

                if ($result && ($result->getType() == Main\EventResult::ERROR)) {
                    if ($contextName === 'mutation') {
                        throw new UserError('Не удалось добавить товар в корзину');
                    } else {
                        // $basketItem->delete();
                    }
                }
            }
        }
    }

    function mutationSync($clientRows, Basket $basket = null, $save = true)
    {
        $this->setEventContextName('mutation');

        $clientItems = BasketClientItemCollection::createFromRows($clientRows);
        $clientItemsByHash = $clientItems->itemsByHash();

        $basket->getBasketItemsCollectionInstance();

        $itemsToAdd = $clientItemsByHash;

        foreach ($basket as $basketItem) {
            $basketItemHash = $basketItem->getBasketHash();
            $clientItem = $itemsToAdd[$basketItemHash];
            if ($clientItem) {
                $basketItem->setFieldNoDemand('QUANTITY', $clientItem['QUANTITY']);
            } else {
                $basketItem->delete();
            }
            unset($itemsToAdd[$basketItemHash]);
        }

        if (!empty($itemsToAdd)) {
            foreach ($itemsToAdd as $clientItem) {
                $basketItem = $clientItem->createBasketItem($basket, []);
                if ($basketItem) {
                    $basketItem->setRelated('IS_NEW', true);
                }
            }
        }
        $basket->save();
    }

    function mutationAddMultiple($args, $basket = null, $save = true)
    {
        $basket = $basket ?: $this->getBasket();

        if ($save) {
            $order = $basket->getOrder();
            $basket->setOrderEmpty();
            $this->setEventContextName('mutation');
        }

        if (!empty($args['items'])) {
            foreach ($args['items'] as $item) {
                $this->mutationAdd($item, false);
            }
        }

        if ($save) {
            $basket->save();
            if ($order)
                $basket->setOrder($order);
            $this->setEventContextName(null);
        }

        return true;
    }

    function mutationAdd($args, $basket = null, $save = true)
    {
        $basket = $basket ?: $this->getBasket();

        if ($save) {
            $this->setEventContextName('mutation');
        }

        $inputItem = new BasketItemInput($args);

        foreach ($basket as $item) {
            if ($item->getField('PRODUCT_ID') == $inputItem->getProductId()) {
                if ($item->getInputHash() === $inputItem->getHash()) {
                    $basketItem = $item;
                }
            }
        }

        if ($basketItem) {
            $basketItem->setFieldNoDemand('QUANTITY', $basketItem->getQuantity() + $inputItem->getQuantity());
        } else {
            $basketItem = $inputItem->createBasketItem($basket, []);
        }

        if ($save) {
            $basket->save();
            $this->setEventContextName(null);
        }

        return $basketItem;
    }


    /**
     * @return Basket
     */
    function getBasket($refetch = false)
    {
        if (!isset($this->basket) || $refetch) {
            $this->basket = Basket::loadItemsForFUser(
                Bitrix\Sale\Fuser::getId(),
                Bitrix\Main\Context::getCurrent()->getSite()
            );
            $this->basket->itemsLoadAll();
        }
        return $this->basket;
    }

    function setBasket($basket)
    {
        $this->basket = $basket;

        return $this->basket;
    }

    function mutationItemComment($basket, $args)
    {
        $basketItem = $this->findBasketItem($basket, $args);

        if (!$basketItem) {
            return;
        }

        $propCollection = $basketItem->getPropertyCollection();

        $propItem = null;

        foreach ($propCollection as $prop) {
            if ($prop->getField('CODE') === 'COMMENT') {
                $propItem = $prop;
                break;
            }
        }

        if (!$propItem)
            $propItem = $propCollection->createItem();

        $propItem->setFields([
            'NAME' => 'Комментарий',
            'CODE' => 'COMMENT',
            'VALUE' => $args['comment']
        ]);

        $propCollection->save();
    }

    function mutationQuantitySet($args, $basket = null, $save = true)
    {
        return $this->_mutationQuantity($args, 'set', $basket, $save);
    }

    function _mutationQuantity($args, $op = 'set', $basket = null, $save = true)
    {
        $basket = $basket ?: $this->getBasket();

        $basketItem = $this->findBasketItem($basket, $args);

        if (!$basketItem)
            return;

        if ($basketItem->getPropValue('IS_GIFT') === 'Y')
            return;

        $quantity = $args['quantity'] ?? 1;

        if ($op == 'set') {
            $updateQuantity = round($quantity, 2);
        } else {
            $updateQuantity = $basketItem->getField('QUANTITY') + $quantity;
        }

        $basketItem->setFieldNoDemand('QUANTITY', $updateQuantity);

        if ($save)
            $basket->save();

        return true;
    }

    function findBasketItem($basket, $args)
    {
        $basketItem = false;

        if (!isset($args['itemId'])) {
            if (isset($args['productId'])) {
                $basketItem = $basket->getBasketItemByProductId('catalog', $args['productId']);
            } else if (isset($args['itemHash'])) {
                foreach ($basket->getBasketItems() as $item) {
                    if ($item->getHash() === $args['itemHash']) {
                        $basketItem = $item;
                        break;
                    }
                }
            }
        } else {
            $basketItem = $basket->getItemById($args['itemId']);
        }

        return $basketItem;
    }

    function findBasketItemOrFail($basket, $args)
    {
        $basketItem = $this->findBasketItem($basket, $args);

        if (!$basketItem) throw new \Exception('Error');

        return $basketItem;
    }

    function mutationQuantityAdd($args)
    {
        return $this->_mutationQuantity($args, 'add');
    }

    function mutationRemove($args, $basket = null, $save = true)
    {
        $basket = $basket ?: $this->getBasket();

        $basketItem = $this->findBasketItem($basket, $args);

        if (!$basketItem)
            return false;

        $basketItem->delete();

        if ($save)
            $basket->save();

        return true;
    }

    function mutationClear($args = [], $basket = null)
    {
        $basket = $basket ?: $this->getBasket();
        $basket->clearCollection();
        $basket->save();
        return true;
    }

    function mutationAppendBasket(Basket $basketToAdd)
    {

        $filterFields = array(
            'SET_PARENT_ID', 'TYPE',
            'PRODUCT_ID', 'PRODUCT_PRICE_ID', 'PRICE', 'CURRENCY', 'WEIGHT', 'QUANTITY', 'LID',
            'NAME', 'CALLBACK_FUNC', 'NOTES', 'PRODUCT_PROVIDER_CLASS', 'CANCEL_CALLBACK_FUNC',
            'ORDER_CALLBACK_FUNC', 'PAY_CALLBACK_FUNC', 'DETAIL_PAGE_URL', 'CATALOG_XML_ID', 'PRODUCT_XML_ID',
            'VAT_RATE', 'MEASURE_NAME', 'MEASURE_CODE', 'BASE_PRICE', 'VAT_INCLUDED'
        );

        $basket = $this->getBasket();

        $orderBasketItems = $basketToAdd->getOrderableItems();

        /** @var BasketItem $oldBasketItem */
        foreach ($orderBasketItems as $oldBasketItem) {
            $propertyList = array();

            if ($oldPropertyCollection = $oldBasketItem->getPropertyCollection()) {
                $propertyList = $oldPropertyCollection->getPropertyValues();
            }

            $item = $basket->getExistsItem($oldBasketItem->getField('MODULE'), $oldBasketItem->getField('PRODUCT_ID'), $propertyList);

            if ($item) {
                $item->setField('QUANTITY', $item->getQuantity() + $oldBasketItem->getQuantity());
            } else {

                $item = $basket->createItem($oldBasketItem->getField('MODULE'), $oldBasketItem->getField('PRODUCT_ID'));
                $item->setField('CURRENCY', CurrencyManager::getBaseCurrency());
                $fieldValues = $oldBasketItem->getFieldValues();
                $fieldValuesToTransfer = [];

                foreach ($filterFields as $filterField) {
                    $fieldValuesToTransfer[$filterField] = $fieldValues[$filterField];
                }

                $item->setField('NAME', $fieldValues['NAME']);

                $resultItem = $item->setFields($fieldValuesToTransfer);

                if (!$resultItem->isSuccess())
                    continue;
                /** @var Sale\PropertyValueCollection $newPropertyCollection */
                $newPropertyCollection = $item->getPropertyCollection();

                /** @var Sale\BasketPropertyItem $oldProperty */
                foreach ($propertyList as $oldPropertyFields) {
                    $propertyItem = $newPropertyCollection->createItem($oldPropertyFields);
                    unset($oldPropertyFields['ID'], $oldPropertyFields['BASKET_ID']);

                    /** @var Sale\BasketPropertyItem $propertyItem */
                    $propertyItem->setFields($oldPropertyFields);
                }

            }
        }
        $res = $basket->save();
        return true;
    }
}



