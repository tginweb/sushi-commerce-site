<?php

namespace Push\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use Main\Entity\Model\ModelCollection;

class PushSubscriberModel extends D7Model
{
    use Containerable;

    public static function query()
    {
        return new PushSubscriberQuery(static::instantiateAdapter(), get_called_class(), ModelCollection::class);
    }

    public static function tableName()
    {
        return 'push_subscribers';
    }

    public function onBeforeSave()
    {
        if (!$this['UF_SUBSCRIPTION_ID']) {
            $this['UF_SUBSCRIPTION_ID'] = $this->generateSubscriptionId();
        }

        if (!$this['UF_CREATED_AT']) {
            $this['UF_CREATED_AT'] = new \Bitrix\Main\Type\DateTime();
        }

        $this['UF_UPDATED_AT'] = new \Bitrix\Main\Type\DateTime();
    }

    private function generateSubscriptionId()
    {
        return 'sub_' . uniqid() . '_' . time();
    }

    public function isActive()
    {
        return $this['UF_ACTIVE'] === 'Y';
    }

    public function getEndpoint()
    {
        return $this['UF_ENDPOINT'];
    }

    public function getP256dhKey()
    {
        return $this['UF_P256DH_KEY'];
    }

    public function getAuthToken()
    {
        return $this['UF_AUTH_TOKEN'];
    }
}
