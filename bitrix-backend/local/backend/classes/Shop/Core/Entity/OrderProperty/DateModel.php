<?php

namespace Shop\Core\Entity\OrderProperty;

use Shop\Core\Entity\OrderProperty;
use function TG\Shop\Core\Entity\OrderProperty\FormatDate;

class Date extends OrderProperty
{
    public function getEnumOptions($value = null, $order = null)
    {
        $datetime = new \DateTime();

        $options = [];

        for ($i = 0; $i < 30; $i++) {
            $dateValue = $datetime->format('d.m.Y');

            //$day = FormatDate('d', $datetime->getTimestamp());

            $options[$dateValue] = [
                'NAME' => FormatDate('d F', $datetime->getTimestamp()),
                'VALUE' => $dateValue
            ];


            $datetime->modify('+1 day');
        }
        return array_values($options);
    }
}


