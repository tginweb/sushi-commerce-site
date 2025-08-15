<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;
use Shop\Core\Entity\VorderCurrent;

class Phone extends OrderProperty
{
    public function vorderUpdateFromClient(VorderCurrent $vorder, $value, $valueChanged = null, &$inputAttrsByType = [], $op = null)
    {
        if ($valueChanged) {
            $this->container->getLoggerService()->addEvent([
                'name' => 'VORDER_PHONE_CHANGED',
                'data' => [
                    'VORDER_ID' => $vorder->getId(),
                    'PHONE' => $value
                ]
            ])->queueAdd();
        }
    }
}


