<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\BonusLevel;
use Shop\Core\Entity\ClientCard;
use Shop\Core\Entity\OrderModel;

class ClientCardService extends BaseService
{
    public $currentCard;
    public $bonusLevels;

    function getCurrentClientDiscounts($phone = null)
    {
        if ($this->container->getUserId()) {
            $card = $this->getCurrentUserCard();
        } else {
            if (!$phone && $_SESSION['SALE_GUEST_CLIENT_PHONE'])
                $_SESSION['SALE_GUEST_CLIENT_PHONE'] = $phone;
            if ($phone)
                $card = $this->findByPhone($phone);
        }
        return $this->container->getDiscountService()->getDiscounts()->filterByClientCard($card);
    }

    function getBonusLevels()
    {
        if (!isset($this->bonusLevels)) {
            $this->bonusLevels = BonusLevel::query()->withViewList()->sort('SORT', 'ASC')->getList();
        }
        return $this->bonusLevels;
    }

    function fetchServiceCard($phone)
    {
        $service1c = $this->container->get1СService();
        $response = $service1c->apiClientCard($phone);
        return $response;
    }

    function findOrCreateByPhone($phone, $userId = null)
    {
        $card = $this->findByPhone($phone);
        if (!$card)
            $card = $this->createNewCard($phone, $userId);
        return $card;
    }

    function findByPhone($phone)
    {
        $card = ClientCard::query()
            ->filter([
                'UF_CLIENT_PHONE' => $phone
            ])
            ->first();

        return $card;
    }

    function createNewCard($phone, $userId = null)
    {
        $card = new ClientCard(null, []);
        $card['UF_CLIENT_PHONE'] = $phone;
        if ($userId)
            $card['UF_USER_ID'] = $userId;
        $card->save();
        return $card;
    }

    function getCurrentUserCard()
    {
        if (!isset($this->currentCard)) {

            $this->currentCard = false;

            $user = $this->container->getUser();

            if ($user) {
                $phone = $user->getPhone();
            } else {
                $phone = $_SESSION['SALE_GUEST_CLIENT_PHONE'];
            }

            if (!$phone)
                return false;

            $this->currentCard = $this->findOrCreateByPhone($phone);
        }

        return $this->currentCard;
    }

    function getCurrentUserBonuses($refetch = false)
    {
        $card = $this->getCurrentUserCard();
        $card->fetch($refetch);
        return $card->getBonuses();
    }

    function getUserBonuses($userId, $refetch = false)
    {
        $card = $this->getCurrentUserCard();

        if (!$card) return 0;

        $card->fetch($refetch);
        return $card->getBonuses();
    }

    function getUserBonusesPercent($userId, $refetch = false)
    {
        $card = $this->getCurrentUserCard();
        if (!$card) return 0;
        $card->fetch($refetch);
        return $card->getBonusesPercent();
    }

    function getOrderDiscounts(OrderModel $order, $refetch = false)
    {
        return $order->computed('clientCardDiscounts', function () use ($order) {
            return $this->getCurrentClientDiscounts($order->getPhone());
        }, $refetch);
    }

    function getOrderDiscount(OrderModel $order, $refetch = false)
    {
        return $order->computed('clientCardDiscount', function () use ($order, $refetch) {
            $discounts = $this->getOrderDiscounts($order, $refetch);
            $discounts = $discounts->filterActual($order);
            return $discounts->count() ? $discounts->getMaximal() : false;
        }, $refetch);
    }
}



