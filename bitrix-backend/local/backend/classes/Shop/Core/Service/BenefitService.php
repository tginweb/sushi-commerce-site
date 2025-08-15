<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\OrderModel;

class BenefitService extends BaseService
{
    public $discounts = [];

    function canUseOrderBenefitType(OrderModel $order, $benefitType = null)
    {
        return $this->checkOrderBenefitType($order, $benefitType) === $benefitType;
    }

    function checkOrderBenefitType(OrderModel $order, $benefitType = null, $change = false)
    {
        if (!$benefitType) {
            $benefitType = $order->getBenefitType();
        }

        $bonusesUsed = $order->getBonusesUseChecked();
        $couponsUsed = $order->isCouponsUsed();
        $discount = $this->container->getSaleClientCardService()->getOrderDiscount($order);

        switch ($benefitType) {
            case 'bonus':
                if (!$bonusesUsed || $couponsUsed)
                    $benefitType = null;
                break;
            case 'coupon':
                if (!$couponsUsed || $bonusesUsed)
                    $benefitType = null;
                break;
            case 'discount':
                if (!$discount || $bonusesUsed || $couponsUsed)
                    $benefitType = null;
                break;
        }

        if ($benefitType)
            return $benefitType;

        if ($bonusesUsed) {
            if ($change) {
                $order->couponsDelete();
            }
            return 'bonus';
        } else if ($couponsUsed) {
            if ($change) {
                $order->bonusesDelete();
            }
            return 'coupon';
        } else {
            if ($discount) {
                return 'discount';
            } else {
                return 'bonus';
            }
        }
    }

    function getBenefits()
    {
        $res = [];

        $res['discount'] = [
            'NAME' => 'Скидка',
            'VALUE' => 'discount',
        ];

        $res['bonus'] = [
            'NAME' => 'Бонусы',
            'VALUE' => 'bonus',
        ];

        $res['promocode'] = [
            'NAME' => 'Промокод',
            'VALUE' => 'promocode',
        ];

        return $res;
    }
}



