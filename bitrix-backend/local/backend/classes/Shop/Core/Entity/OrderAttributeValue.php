<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ArrayableModel;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributeValue extends ArrayableModel
{
    /** @var IOrderAttribute */
    public $attribute;

    public $value;
    public $rawValue;

    public function __construct($attribute, $rawValue = null, $valueFields = [])
    {
        $this->attribute = $attribute;
        $this->rawValue = $rawValue;
        $this->fields = $valueFields;
    }

    function getAttribute()
    {
        return $this->attribute;
    }

    function getRawValue()
    {
        return $this->rawValue;
    }

    function getValue($field = null, $validate = false)
    {
        return $this->getAttribute()->prepareValueMultiplying($this->getRawValue(), $field, null, $validate);
    }
}


