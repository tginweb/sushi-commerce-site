<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;
use Shop\Core\Lib\IOrderAttribute;

class OrderField extends ArrayableModel implements IOrderAttribute
{
    use Containerable;
    use OrderAttributeTrait;

    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    function getAttrKind()
    {
        return 'field';
    }

    function getAttrType()
    {
        return $this['TYPE'];
    }

    function getCode()
    {
        return $this['CODE'];
    }

    function getName()
    {
        return $this['NAME'];
    }

    function isMultiple()
    {
        return !!$this['MULTIPLE'];
    }

    function isProfile()
    {
        return !!$this['IS_PROFILE'];
    }

    function getDefaultValue()
    {
        return $this['DEFAULT_VALUE'];
    }

    function getDescription()
    {

    }

    function getParam($name)
    {
        return null;
    }

    function getEnumOptions($value = null, $order = null)
    {
        return [];
    }


}


