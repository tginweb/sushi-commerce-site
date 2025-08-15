<?php

namespace Main\Guard;

use Main\DI\Container;
use Main\Error\AccessError;

class RateGuard extends BaseGuard
{
    function access($context = null, $args = [])
    {
        $rateKey = $this->args['key'];
        $rateRules = $this->args['rules'];

        $rateService = Container::instance()->getRateService();

        $res = $rateService->checkRate($rateKey, $rateRules, $args);

        if ($res !== true) {
            return new AccessError(null, 'RATE_LIMIT', ['ttl' => $res]);
        }

        return true;
    }
}


