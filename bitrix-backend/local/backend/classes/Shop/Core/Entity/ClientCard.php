<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use Main\Lib\Date\DateTimeDb;
use Project\C1\Core\Provider\Soap;
use TG\Main\Helper;
use function TG\Shop\Core\Entity\FormatDate;

class ClientCard extends D7Model
{
    use Containerable;

    public $levelModel;

    public static function tableName()
    {
        return 'sale_client_card';
    }

    public static function getPropsInfo()
    {
        return parent::getPropsInfo() + [
                'BONUSES' => ['view' => true],
                'FETCHED' => ['view' => true],
                'FETCHED_TIME' => ['view' => true],
                'USER_ID' => ['view' => true],
                'CLIENT_PHONE' => ['view' => true],
                'LEVEL_CODE' => ['view' => true],
                'LEVEL_NAME' => ['view' => true],
                'MONTH_SPENT' => ['view' => true],
                'BONUSES_EXPIRE' => ['view' => true],
                'BONUSES_PERCENT' => ['view' => true],
                'DIS_FIRST_ORDER' => ['view' => true],
                'DIS_SELF_PICKUP' => ['view' => true],
                'TRANSPORT' => ['view' => true],
            ];
    }

    public function getNameSearchable($name)
    {
        return preg_replace('/[^\w]/iu', '', mb_strtoupper($name));
    }

    public function fetch($refetch = false)
    {
        if (!$refetch && $this->isFetchedActual())
            return;

        $info = $this->container->getSaleClientCardService()->fetchServiceCard($this->getPhone());

        if (!$info)
            return;

        if ($info === Soap::ERROR_NOT_FOUND) {
            $info = [
                'cardStatus' => 'base'
            ];
        }

        $bonusLevels = $this->container->getSaleClientCardService()->getBonusLevels();
        $bonusLevelsByName = $bonusLevels->reduce(function ($acc, $level) {
            $acc[$this->getNameSearchable($level['NAME'])] = $level;
            return $acc;
        }, []);

        $serviceLevelName = $this->getNameSearchable($info['cardStatus']);

        $bonusLevel = $bonusLevelsByName[$serviceLevelName];

        if (!$bonusLevel) {
            $bonusLevel = $bonusLevels->first();
        }

        if (!empty($info['lastOrders'])) {
            $lastOrder = $info['lastOrders'][0];
            $lastOrderDateTs = strtotime($lastOrder['Date']);
        }

        if ($lastOrderDateTs) {
            $this['UF_BONUSES_EXPIRE'] = DateTimeDb::fromTimestamp($lastOrderDateTs + 3600 * 24 * 90);
        } else {
            $this['UF_BONUSES_EXPIRE'] = null;
        }

        $this['UF_FETCHED'] = true;
        $this['UF_FETCHED_TIME'] = FormatDate('FULL', time());
        $this['UF_BONUSES'] = intval($info['bonuses']);

        $this['UF_BONUSES_PERCENT'] = $info['procent_oplata_bonus'];
        $this['UF_DIS_FIRST_ORDER'] = $info['skidka_perv'];
        $this['UF_DIS_SELF_PICKUP'] = $info['skidka_samov'];

        $this['UF_LEVEL_NAME'] = $info['cardStatus'];
        $this['UF_LEVEL_CODE'] = $bonusLevel['CODE'];
        $this['UF_MONTH_SPENT'] = intval($info['month']);
        $this->save();

    }

    public function isExpired()
    {
        return !$this->isFetched() || ($this->getFetchedAge() > \Main\Helper\Date::DURATION_DAY * 2);
    }

    public function isFetchedActual()
    {
        return $this->isFetched() && ($this->getFetchedAge() < \Main\Helper\Date::DURATION_MINUTE * 15);
    }

    public function isFetched()
    {
        return !!$this['UF_FETCHED'];
    }

    public function getFetchedAge()
    {
        return time() - DateTimeDb::parseToTimestamp($this['UF_FETCHED_TIME']);
    }

    public function getPhone()
    {
        return $this['UF_CLIENT_PHONE'];
    }

    public function getLevelCode()
    {
        return $this['UF_LEVEL_CODE'];
    }

    public function getBonuses()
    {
        return $this['UF_BONUSES'];
    }

    public function getBonusesPercent()
    {
        return $this['UF_BONUSES_PERCENT'];
    }

    public function getDiscountSelfPickup()
    {
        return $this['UF_DIS_SELF_PICKUP'];
    }

    public function getDiscountFirstOrder()
    {
        return 0;
        return $this['UF_DIS_FIRST_ORDER'];
    }

    function getDiscounts()
    {
        $discounts = [];

        return new DiscountCollection($discounts);
    }

    function getLevelModel()
    {
        if (!isset($this->levelModel)) {
            $levels = $this->container->getSaleClientCardService()->getBonusLevels()->pluckArray(null, 'CODE');
            $this->levelModel = $levels[$this['UF_LEVEL_CODE']] ?: null;
            //$this->levelModel = $levels[10] ?: false;
        }
        return $this->levelModel;
    }
}
