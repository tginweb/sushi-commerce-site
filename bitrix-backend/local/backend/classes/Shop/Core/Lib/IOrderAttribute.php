<?php

namespace Shop\Core\Lib;

use Shop\Core\Entity\OrderAttributeValue;

interface IOrderAttribute
{
    function getAttrKind();

    function getAttrType();

    function getCode();

    function getName();

    function isMultiple();

    function isProfile();

    function getDefaultValue();

    function getDescription();

    function getEnumOptions($value = null, $order = null);

    function getGraphqlType();

    function getGraphqlInputType();

    function prepareValueFields($value, $order, $validate);

    function prepareValue($value, $field = 'VALUE', $order = null, $validate = false);

    function prepareValueMultiplying($value, $field = 'VALUE', $order = null, $validate = false);

    function getParam($name);

    function isEnum();

    function haveView();

    /**
     * @param IOrderAttribute $attribute
     * @param mixed $rawValue
     * @param array $fields
     * @return OrderAttributeValue
     */
    function createValueEntity($rawValue, $fields);

    /**
     * @return OrderAttributeValue
     */
    function getValueEntity();

    function setValueEntity(OrderAttributeValue $value);


    function valueEncode($value);

    function valueDecode($value, $forceSingle = false);
}



