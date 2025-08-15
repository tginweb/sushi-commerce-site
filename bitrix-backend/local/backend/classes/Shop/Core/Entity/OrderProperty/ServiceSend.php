<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class ServiceSend extends OrderProperty
{
    public function getEnumOptions($value = null, $order = null)
    {
        $options = parent::getEnumOptions();

        $iconsByValue = [
            'cash' => 'payment_cash',
            'card' => 'payment_terminal',
            'online' => 'payment_card',
        ];

        $options = array_map(function ($option) use ($iconsByValue) {
            $option['ICON'] = $iconsByValue[$option['VALUE']];
            return $option;
        }, $options);

        return $options;
    }
}


