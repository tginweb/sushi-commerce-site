<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\Coupon;

class CouponService extends BaseService
{
    public function fetchFromService($couponCode)
    {
        $data = $this->container->get1СService()->getProviderWs()->apiGetPromocode([
            'code' => $couponCode
        ]);
        if ($data && $data['result'] === 'success') {
            return $data;
        }
    }

    public function ensurePromocode($couponCode)
    {
        $couponCodeNormalized = preg_replace('/[^\w\d]/uis', '', mb_strtoupper($couponCode));

        $query = Coupon::query()->filter(['NAME' => $couponCodeNormalized])->withViewList();
        $model = $query->first();

        if ($model) {
            if ($model->getFetchedAge() > 3600) {
                $serviceData = $this->fetchFromService($couponCode);
                if ($serviceData) {

                }
            }
        } else {
            $serviceData = $this->fetchFromService($couponCode);
            if ($serviceData) {
                $model = Coupon::createFromService($serviceData);
                $model['NAME'] = $couponCodeNormalized;
                $model->save();
            }
        }
        return $model;
    }
}



