<?php

namespace Push\Core\Entity;

use Main\Entity\D7\HLQuery;

class PushSubscriberQuery extends HLQuery
{
    public function filterByUser($userId)
    {
        return $this->filter(['UF_USER_ID' => $userId]);
    }

    public function filterByEndpoint($endpoint)
    {
        return $this->filter(['UF_ENDPOINT' => $endpoint]);
    }

    public function filterActive()
    {
        return $this->filter(['UF_ACTIVE' => 'Y']);
    }

    public function filterBySubscriptionId($subscriptionId)
    {
        return $this->filter(['UF_SUBSCRIPTION_ID' => $subscriptionId]);
    }
}
