<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributeCollection extends ModelCollection
{
    function filterIsProfile()
    {
        return $this->filter(function (IOrderAttribute $item) {
            return $item->isProfile();
        });
    }

    function filterIsProp()
    {
        return $this->filter(function (IOrderAttribute $item) {
            return $item->getAttrKind() === 'prop';
        });
    }

    function filterIsField()
    {
        return $this->filter(function (IOrderAttribute $item) {
            return $item->getAttrKind() === 'field';
        });
    }

    function getAttributesValues()
    {
        $values = array_map(function (IOrderAttribute $item) {
            return $item->getValueEntity();
        }, $this->all());
        return new OrderAttributeValueCollection($values);
    }
}


