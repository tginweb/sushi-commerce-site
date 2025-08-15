<?php

namespace Shop\Core\Entity;

use Main\Graphql\Types;
use Main\Helper\Str;
use Main\Lib\Date\DateBase;
use Main\Lib\Date\DateTime;

trait OrderAttributeTrait
{
    public $value;

    abstract function getAttrType();

    abstract function getEnumOptions($value = null, $order = null);

    abstract function getParam($name);

    abstract function isMultiple();

    public function getCamelCode()
    {
        return Str::camelCase($this->getCode(), true);
    }

    public function getGraphqlType()
    {
        switch ($this->getAttrType()) {
            case 'string':
                return Types::string();
            case 'number':
                return Types::int();
            case 'boolean':
                return Types::boolean();
            case 'enum':
                return Types::enumInstance('Attr' . $this->getCamelCode() . 'Enum', $this->getEnumOptions());
            case 'date':
                return Types::int();
            case 'json':
                return Types::JSON();
            default:
                return Types::string();
        }
    }

    public function getGraphqlInputType()
    {
        switch ($this->getAttrType()) {
            case 'string':
                return Types::string();
            case 'number':
                return Types::int();
            case 'boolean':
                return Types::boolean();
            case 'enum':
                return Types::enumInstance('Attr' . $this->getCamelCode() . 'Enum', $this->getEnumOptions());
            case 'date':
                return Types::int();
            case 'json':
                return Types::JSON();
            default:
                return Types::string();
        }
    }

    public function prepareValueMultiplying($value, $field = 'VALUE', $order = null, $validate = false)
    {
        if ($this->isMultiple()) {
            $value = !empty($value) ? $value : [];
            return array_map(function ($itemValue) use ($field, $order, $validate) {
                return $this->prepareValue($itemValue, $field, $order, $validate);
            }, $value);
        } else {
            return $this->prepareValue($value, $field, $order, $validate);
        }
    }

    public function prepareValue($value, $field = 'VALUE', $order = null, $validate = false)
    {
        $result = $this->prepareValueFields($value, $order, $validate);
        return $field ? $result[$field] : $result;
    }

    public function isEnum()
    {
        return $this['TYPE'] === 'enum';
    }

    public function prepareValueFields($value, $order = null, $validate = false)
    {
        $valueView = $value;

        if ($this->isEnum()) {
            if (is_numeric($value))
                $value = intval($value);
            $options = $this->getEnumOptionsCached($value, $order);
            if (!empty($options)) {
                if (!$value || empty($options[$value])) {
                    if ($validate && $this->getParam('VALUE_IN_OPTIONS')) {
                        $valueOption = current($options);
                        $value = $valueOption['VALUE'];
                    }
                }
            }
            $valueOption = $options[$value];
            if ($valueOption) {
                $valueView = $valueOption['NAME'];
            } else {
                $valueView = '';
            }
        }

        $value = $this->valueDecode($value, true);

        if ($value === '')
            $value = null;

        switch ($this->getAttrType()) {
            case 'boolean':
                $valueView = $value ? 'да' : 'нет';
                break;
            case 'date':
                $valueView = DateTime::parseAndFormat($value, DateBase::FORMAT_DATETIME);
                break;
            case 'json':
                $valueView = '';
                break;
        }

        return [
            'VALUE' => $value,
            'VIEW' => $valueView,
        ];
    }

    public function setValueEntity(OrderAttributeValue $value)
    {
        $this->value = $value;
    }

    /**
     * @return OrderAttributeValue
     */
    public function getValueEntity()
    {
        if (!$this->value)
            $this->value = $this->createValueEntity();
        return $this->value;
    }

    function createValueEntity($rawValue = null, $fields = [])
    {
        return new OrderAttributeValue($this, $rawValue, $fields);
    }

    public $enumOptions;
    public $enumOptionsFilter;

    function getEnumOptionsCached()
    {
        if (!isset($this->enumOptions)) {
            $this->enumOptions = $this->getEnumOptions();
        }
        return $this->enumOptions;
    }

    function setEnumOptions($options)
    {
        $this->enumOptions = $options;
    }

    function setEnumOptionsFilter($callback)
    {
        $this->enumOptionsFilter = $callback;
    }

    function getEnumOptionsFiltered()
    {
        $options = $this->getEnumOptions();
        if ($this->enumOptionsFilter) {
            $cb = $this->enumOptionsFilter;
            $options = $cb($options);
        }
        return $options;
    }

    function getEnumOptionsFilteredList()
    {
        $result = [];
        $options = $this->getEnumOptionsFiltered();
        foreach ($options as $code => $option) {
            $result[] = [
                    'VALUE' => $code,
                ] + $option;
        }
        return $result;
    }

    function haveView()
    {
        $type = $this->getAttrType();
        return $this->isEnum() || in_array($type, ['boolean', 'date']);
    }

    function valueEncodeSingle($value)
    {
        switch ($this->getAttrType()) {
            case 'boolean':
                $value = $value ? 'Y' : 'N';
                break;
            case 'date':
                $value = $value ? DateTime::parse($value)->formatToDateTime(true) : null;
                break;
            default:
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                break;
        }
        return $value;
    }

    function valueEncode($value)
    {
        if ($this->isMultiple()) {
            if (is_array($value)) {
                foreach ($value as $i => $item) {
                    $value[$i] = $this->valueEncodeSingle($item);
                }
            } else {
                $value = [];
            }
        } else {
            $value = $this->valueEncodeSingle($value);
        }
        return $value;
    }

    function valueDecodeSingle($value)
    {
        switch ($this->getAttrType()) {
            case 'string':
                $value = $value ?: '';
                break;
            case 'number':
                $value = intval($value);
                break;
            case 'boolean':
                $value = !($value === 'N' || !$value);
                break;
            case 'date':
                $time = DateTime::parseFromDateTime($value);
                $value = $value ? $time->getTimestamp() : null;
                break;
            case 'json':
                if (is_string($value)) {
                    $value = json_decode($value, true);
                }
                break;
        }
        return $value;
    }

    function valueDecode($value, $forceSingle = false)
    {
        if ($this->isMultiple() && !$forceSingle) {
            if (is_array($value)) {
                foreach ($value as $i => $item) {
                    $value[$i] = $this->valueDecodeSingle($item);
                }
            } else {
                $value = [];
            }
        } else {
            $value = $this->valueDecodeSingle($value);
        }
        return $value;
    }
}


