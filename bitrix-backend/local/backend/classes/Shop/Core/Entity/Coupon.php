<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementModel;
use function TG\Shop\Core\Entity\FormatDate;
use function TG\Shop\Core\Entity\MakeTimeStamp;

class Coupon extends ElementModel
{
    static function getPropsInfo()
    {
        return [
            'NAME' => ['view' => true, 'cfilter' => true],
            'CODE' => ['view' => true, 'cfilter' => true],
        ];
    }

    static function createFromService($data)
    {
        $coupon = Coupon::create([]);

        $coupon->setPropValues([
            'ACTIVE_FROM_DATE' => $data['activeFromDate'],
            'ACTIVE_TO_DATE' => $data['activeToDate'],
            'COUPON' => $data['code'],
            'MIN_PRICE' => $data['minPrice'],
            'FETCHED_TIME' => FormatDate('FULL')
        ]);

        if ($data['giftProductId']) {
            foreach ($data['giftProductId'] as $productId) {
                $productId = trim($productId);
                if ($productId)
                    $coupon->setPropValue('PRODUCT_ID', $productId);
            }
        }

        $coupon->setFieldValue('NAME', $data['code']);

        return $coupon;
    }

    function getCouponCode()
    {
        return $this->getProp('COUPON');
    }

    function getFetchedTimestamp()
    {
        return MakeTimeStamp($this->getProp('FETCHED_TIME'));
    }

    function getFetchedAge()
    {
        return time() - $this->getFetchedTimestamp();
    }

    function getProductId()
    {
        return $this->getProp('PRODUCT_ID');
    }

    function matchCode($matchCode)
    {
        $matchCodeNormalized = preg_replace('/[^\w\d]/uis', '', mb_strtoupper($matchCode));
        $currentCodeNormalized = preg_replace('/[^\w\d]/uis', '', mb_strtoupper($this->getCouponCode()));

        return $matchCodeNormalized === $currentCodeNormalized;
    }

}
