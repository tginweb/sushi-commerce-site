<?php

namespace Shop\Core\Entity\OrderField;

use Shop\Core\Entity\OrderField;

class PaySystemId extends OrderField
{
    public function isEnum()
    {
        return true;
    }

    public function getEnumOptions($value = null, $order = null)
    {
        $list = $this->container->getPaymentService()->getPaySystems();
        $result = [];
        foreach ($list as $id => $item) {
            $result[$id] = [
                'NAME' => $item['NAME'],
            ];
        }
        return $result;
    }
}


