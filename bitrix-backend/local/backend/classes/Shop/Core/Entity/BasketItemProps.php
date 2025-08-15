<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ArrayableModel;

class BasketItemProps extends ArrayableModel
{
    function setPropertyCollection($collection)
    {
        foreach ($collection as $item) {
            $this[$item->getField('CODE')] = $item->getFields()->getValues();
        }
    }

    function forBasketItemCollection()
    {

        $result = [];

        foreach ($this->fields as $code => $prop) {

            unset($prop['BASKET_ID']);

            if (!$prop['CODE'])
                $prop['CODE'] = $code;

            if (!$prop['XML_ID'])
                $prop['XML_ID'] = $code;

            if (!$prop['NAME'])
                $prop['NAME'] = $code;

            $result[] = $prop;
        }

        return $result;
    }
}
