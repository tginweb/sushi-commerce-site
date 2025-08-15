<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValueCollection;
use Main\DI\Containerable;
use Main\Entity\Model\Traits\ArrayAccessTrait;

class OrderPropertyValue extends Sale\PropertyValue implements \ArrayAccess
{
    use Containerable;
    use ArrayAccessTrait;

    public $valueDecoded;

    function valueEncode($value)
    {
        //die('--'.json_encode($value));
        $attr = $this->getAttrModel();
        if ($attr) {
            $value = $attr->valueEncode($value);
        }
        return $value;
    }

    function valueDecode($value, $forceSingle = false)
    {
        $attr = $this->getAttrModel();
        if ($attr) {
            $value = $attr->valueDecode($value);
        }
        return $value;
    }

    function setValueDecoded($value)
    {
        $this->valueDecoded = null;
        $this->setValue($this->valueEncode($value));
    }

    function getValueDecoded()
    {
        $value = parent::getValue();

        if (isset($value)) {
            if (!isset($this->valueDecoded)) {
                $this->valueDecoded = $this->valueDecode($value);
            }
        } else {
            $this->valueDecoded = null;
        }

        return $this->valueDecoded;
    }

    function getPropCode()
    {
        $code = $this->getField('CODE');
        if (!$code) {
            $property = $this->getPropModel();
            if ($property) {
                $code = $property->getCode();
                $this->fields->set('CODE', $code);
            }
        }
        return $code;
    }


    function getPropId()
    {
        return $this->fields['ORDER_PROPS_ID'];
    }

    static function getAttributesService()
    {
        static $service;
        if (!$service) {
            $service = self::container()->getOrderAttributesService();
        }
        return $service;
    }

    function getAttrModel()
    {
        return self::getAttributesService()->getAttribute($this->getPropCode());
    }

    function getPropModel()
    {
        return self::getAttributesService()->getProp($this->getPropId());
    }

    static function createForProfile($values)
    {
        $res = [];
        foreach ($values as $key => $val) {
            $res[$key] = static::createPropertyValueObject(null, $val);
        }
        return $res;
    }

    static function createFromArray($fields)
    {
        return static::createPropertyValueObject(null, $fields);
    }


    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        /** @var PropertyValueCollection $collection */
        $collection = $this->getCollection();
        if (!$collection) {
            return null;
        }

        /** @var Order $order */
        $order = $collection->getOrder();
        if (!$order) {
            return null;
        }

        return $order;
    }

}


