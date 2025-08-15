<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;

class TimeMode extends OrderProperty
{
    public function isEnum()
    {
        return true;
    }

    public function getEnumOptions($value = null, $order = null)
    {
        return [
            'nearest' => [
                'NAME' => 'Ближайшее'
            ],
            'custom' => [
                'NAME' => 'Выбранное'
            ],
        ];
    }
}


