<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\Discount;

class DiscountService extends BaseService
{
    public $discounts = null;

    function getDiscounts($refetch = false)
    {
        if (!isset($this->discounts) || $refetch) {
            $discounts = Discount::query()->withViewList()->getList();
            $this->discounts = $discounts;
        }
        return $this->discounts;
    }
}



