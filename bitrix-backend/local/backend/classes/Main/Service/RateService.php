<?php

namespace Main\Service;

use Bitrix\Main\Application;
use Closure;
use Main\Error\RateError;
use TG\Main\Helper;

class RateService extends BaseService
{
    var $config = [];

    function register()
    {
        $this->container->addFilter('response.statuses', function ($list) {
            $list = array_merge($list, $this->getStatuses());
            return $list;
        });
    }

    function getStatuses()
    {
        return ['rate_limit'];
    }

    function checkRateOrThrow($key, $rules, $args = [], &$nextTtl = null)
    {
        $nextTtl = null;

        $res = $this->checkRate($key, $rules, $args, true, $nextTtl);

        if ($res !== true) {
            throw new RateError('Превышен лимит запросов, попробуйте через ' . \Main\Helper\Date::secToStr($res), ['ttl' => $res]);
        }

        return $res;
    }

    function checkRate($key, $rules = [], $args = [], $hit = true, &$nextTtl = null)
    {

        if ($this->container->getConfigService()->get('RATE.DISABLE', false))
            return true;

        if (empty($rules))
            $rules = $this->container->getConfigService()->get('RATE.RULES.' . $key);

        if (empty($rules))
            return true;

        $replaces = $args + $this->replaces();

        $redis = $this->container->getRedisService()->getConnection();

        $device = $this->container->getDeviceDetectService();

        $isMobile = $device->isMobile() || $device->isTablet();

        foreach ($rules as $id => $rule) {

            if (isset($rule['mobile'])) {
                if ($isMobile !== $rule['mobile']) {
                    continue;
                }
            }

            $period = $rule['period'];

            $fullKey = 'rate:' . $key . '-' . $period;

            if (!empty($rule['args'])) {

                if ($rule['args'] instanceof Closure) {
                    $ruleArgs = $rule['args']($replaces);
                } else {
                    $ruleArgs = $rule['args'];
                }

                $ruleArgsResult = [];

                foreach ($ruleArgs as $ruleArgKey => $ruleArgVal) {
                    if (isset($replaces[$ruleArgVal])) {
                        $ruleArgsResult[$ruleArgVal] = $replaces[$ruleArgVal];
                    } else {
                        if (!is_numeric($ruleArgKey)) {
                            $ruleArgsResult[$ruleArgKey] = $ruleArgVal;
                        } else {
                            $ruleArgsResult[] = $ruleArgVal;
                        }
                    }
                }

                if (!empty($ruleArgsResult))
                    $fullKey .= ':' . join('.', $ruleArgsResult);
            }

            $rule['fullKey'] = $fullKey;

            $rules[$id] = $rule;
        }

        usort($rules, function ($a, $b) {
            if ($a['period'] == $b['period']) {
                return 0;
            }
            return ($a['period'] < $b['period']) ? 1 : -1;
        });

        foreach ($rules as $rule) {
            $ruleHits = intval($redis->get($rule['fullKey'])) + 1;

            if ($ruleHits > $rule['limit']) {
                return $redis->ttl($rule['fullKey']);
            }
        }


        foreach ($rules as $rule) {
            if ($hit)
                $redis->incr($rule['fullKey']);
            $redis->expire($rule['fullKey'], $rule['period']);
        }

        foreach ($rules as $rule) {
            $ruleHits = intval($redis->get($rule['fullKey']));

            if ($ruleHits === $rule['limit']) {
                $nextTtl = $redis->ttl($rule['fullKey']);
                break;
            }
        }

        return true;
    }

    function replaces()
    {
        return [
            'SESSION' => $this->container->getSession()->getSessionId(),
            'USER' => $this->container->getUserId(),
            'IP' => $this->getRealIp(),
        ];
    }

    function getRealIp()
    {
        $ip = false;

        $xList = [];

        $xForwarded = Application::getInstance()->getContext()->getServer()->get('HTTP_X_FORWARDED_FOR');
        $xRealIp = Application::getInstance()->getContext()->getServer()->get('HTTP_X_REAL_IP');

        if ($xForwarded)
            $xList[] = $xForwarded;

        if ($xRealIp)
            $xList[] = $xRealIp;

        $xList = join(', ', $xList);

        if (!empty($xList)) {
            $ips = explode(", ", $xList);
            $fCount = count($ips);

            for ($i = 0; $i < $fCount; $i++) {
                if (!preg_match("/^(10|172\\.16|192\\.168)\\./", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        if (!$ip) {
            $ip = trim(Application::getInstance()->getContext()->getRequest()->getRemoteAddress());
        }

        return $ip;
    }
}
