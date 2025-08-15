<?php

namespace Shop\Core\Entity\OrderField;

use Shop\Core\Entity\OrderField;

class DeliveryId extends OrderField
{
    public function isEnum()
    {
        return true;
    }

    public function getEnumOptions($value = null, $order = null)
    {
        $list = $this->container->getDeliveryService()->getDeliveryList();
        $result = [];
        foreach ($list as $id => $delivery) {
            $result[$id] = [
                'NAME' => $delivery['NAME'],
            ];
        }
        return $result;
    }
}


