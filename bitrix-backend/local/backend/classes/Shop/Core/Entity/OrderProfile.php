<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Adapter;
use Main\Entity\D7\D7Model;
use Main\Entity\D7\D7Query;
use Main\Entity\User\UserModel;
use Shop\Core\Graphql\OrderPropValueType;

class OrderProfile extends D7Model
{
    use Containerable;

    const ENTITY_TYPE = 'order.profile';

    static $adapter;

    public $propsByCode;
    public $propsValuesAll;
    public $user;

    public $changedProps = [];

    public static function query()
    {
        return new D7Query(static::instantiateAdapter(), static::class, OrderProfileCollection::class);
    }

    /**
     * Instantiate adapter if it's not instantiated.
     *
     * @return D7Adapter
     */
    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Internals\UserPropsTable::class);
    }


    /**
     * @return UserModel
     */
    function getUser()
    {
        $userId = $this['USER_ID'];

        if (!$userId || $userId < 0)
            return;

        if (!isset($this->user)) {
            $this->user = UserModel::getById($userId);
        }

        return $this->user;
    }

    function getAddressFull()
    {
        $list = [];
        if ($v = $this->getPropValue('ADDRESS')) {
            $list[] = $v;
        }
        if ($v = $this->getPropValue('HOUSE')) {
            $list[] = 'д ' . $v;
        }
        if ($this->getPropValue('PRIVATE_HOUSE') !== 'Y') {
            if ($v = $this->getPropValue('FLAT')) {
                $list[] = 'кв ' . $v;
            }
        }
        return join(', ', $list);
    }

    public function getNameComputed()
    {
        return $this->getPropValue('ADDRESS') . '--' . time();
        if (trim($this->getPropValue('ADDRESS'))) {
            $parts = [
                $this->getPropValue('ADDRESS'),
            ];
        } else {
            $parts = [
                $this->getPropValue('STREET_PATH'),
                $this->getPropValue('HOUSE')
            ];
        }
        $parts = array_filter($parts);
        return $this->getLocalClean(join(', ', $parts));
    }

    public function getLocalClean($address)
    {
        $addressParts = preg_split('/\s*\,\s*/', $address);
        $res = [];
        foreach ($addressParts as $addressPart) {
            if (preg_match('/(Иркутская\s+(область|об|обл)|(область|об|обл)\s+Иркутская)/i', $addressPart)) continue;
            $res[] = $addressPart;
        }
        return join(', ', $res);
    }

    public function getCaptionComputed()
    {
        $res = [];

        if ($this->getPropValue('PRIVATE_HOUSE') === 'Y') {
            if ($this->getPropValue('FLAT')) {
                $res[] = 'Частный дом';
            }
        } else {
            if ($this->getPropValue('FLAT')) {
                $res[] = 'Квартира ' . $this->getPropValue('FLAT');
            }
            if ($this->getPropValue('FLOOR')) {
                $res[] = 'Этаж ' . $this->getPropValue('FLOOR');
            }
        }

        return join(', ', $res);
    }

    public function getCoords()
    {
        $coords = $this->getPropValue('HOUSE_COORDS');
        if ($coords) {
            list ($lat, $lon) = explode(':', $coords);
            return [
                'LAT' => $lat,
                'LON' => $lon,
            ];
        }
    }

    public function setPropValue($code, $value)
    {
        $this->getPropsByCode();
        if (!empty($this->propsByCode[$code])) {
            $this->propsByCode[$code]['VALUE'] = $value;
            $this->changedProps[$code] = $value;
        }
    }

    public function getPropValue($code, $def = null)
    {
        $this->getPropsByCode();
        return !empty($this->propsByCode[$code]) ? $this->propsByCode[$code]['VALUE'] : $def;
    }

    public function getProp($name): OrderPropertyValue
    {
        $this->getPropsByCode();
        return $this->propsByCode[$name];
    }

    public function getPropsByCode()
    {
        if (!isset($this->propsByCode)) {
            $this->propsByCode = [];
            $values = $this->getProps();
            foreach ($values as $value) {
                $this->propsByCode[$value['CODE']] = $value;
            }
        }
        return $this->propsByCode;
    }


    /**
     * @return OrderProperty[]
     */
    public function getProfilePropsAll()
    {
        $result = [];

        /** @var OrderProperty $property */
        foreach ($this->container->getOrderAttributesService()->getProps() as $property) {
            if ($property->isProfile() && ($this['PERSON_TYPE_ID'] == $property->getPersonTypeId())) {
                $result[$property['ID']] = $property;
            }
        }
        return $result;
    }

    public function getProps($refetch = false)
    {
        return $this->getPropsValuesAll($refetch);
    }

    public function getPropsValuesAll($refetch = false)
    {
        if (!isset($this->propsValuesAll) || $refetch) {

            $values = $this->getPropsValues($refetch);
            $result = [];

            foreach ($this->getProfilePropsAll() as $prop) {
                if ($values[$prop['ID']]) {
                    $result[$prop['ID']] = $values[$prop['ID']];
                } else {
                    $result[$prop['ID']] = [
                        'ORDER_PROPS_ID' => $prop['ID'],
                        'CODE' => $prop['CODE'],
                        'NAME' => $prop['NAME'],
                        'USER_PROPS_ID' => $this['ID'],
                        'VALUE' => $prop['DEFAULT_VALUE']
                    ];
                }
            }

            $this->propsValuesAll = OrderPropertyValue::createForProfile($result);
        }

        return $this->propsValuesAll;
    }

    /**
     * @param $refetch
     * @return OrderPropValueType[]
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getPropsValues($refetch = false)
    {
        if (is_null($this->getData('VALUES', null)) || $refetch) {

            $values = [];

            if ($this['ID']) {
                $query = [
                    'select' => [
                        'ID',
                        'USER_PROPS_ID',
                        'ORDER_PROPS_ID',
                        'CODE' => 'PROPERTY.CODE',
                        'PROP_NAME' => 'PROPERTY.NAME',
                        'NAME',
                        'VALUE'
                    ],
                    'filter' => [
                        'USER_PROPS_ID' => $this['ID']
                    ],
                    'order' => [
                        'PROPERTY.SORT' => 'ASC'
                    ]
                ];

                $rs = \Bitrix\Sale\Internals\UserPropsValueTable::getList($query);

                while ($value = $rs->fetch()) {
                    $value['NAME'] = $value['PROP_NAME'];
                    $values[$value['ORDER_PROPS_ID']] = $value;
                }
            }

            $this->setData('VALUES', $values);
        }

        return $this->getData('VALUES');
    }


    public function deleteValues()
    {
        $saleOrderUserPropertiesValue = new \CSaleOrderUserPropsValue;

        $userPropertiesList = $saleOrderUserPropertiesValue::GetList(
            array("SORT" => "ASC"),
            array("USER_PROPS_ID" => $this['ID']),
            false,
            false,
            array("ID", "ORDER_PROPS_ID", "VALUE", "SORT", "PROP_TYPE")
        );

        while ($propertyValues = $userPropertiesList->Fetch()) {
            \Bitrix\Sale\Internals\UserPropsValueTable::delete($propertyValues["ID"]);
        }

        $this->markRefetch();
    }

    public function markRefetch()
    {
        $this->propsByCode = null;
        $this->propsValuesAll = null;
        $this->unsetData('VALUES');
    }

    function extractAddressFlat($value, $delete = false)
    {
        $res = [];
        $parts = preg_split('/\s*,\s*/', $value);
        $flat = null;
        foreach ($parts as $part) {
            if (preg_match('/кв\s+/', $part)) {
                $flat = preg_replace('/[^\d]/', '', $part);
                if ($delete)
                    continue;
            }
            $res[] = $part;
        }
        return [$flat, join(', ', $res)];
    }

    public function updateChangedProps()
    {
        $this->updateValues($this->changedProps);
        $this->changedProps = [];
    }

    public function updateValues($values)
    {
        if (empty($values))
            return;

        $fieldValues = [];
        $fieldValuesTmp = array_map(function ($val) {
            return [
                'VALUE' => $val
            ];
        }, $values);

        foreach ($fieldValuesTmp as $propCode => &$fieldValue) {

            $fieldValue['USER_PROPS_ID'] = $this['ID'];

            $propValueModel = $this->getProp($propCode);

            if (!$propValueModel) {
                continue;
            }

            $propId = $propValueModel['ORDER_PROPS_ID'];

            $fieldValue['CODE'] = $propValueModel['CODE'];
            $fieldValue['VALUE'] = $propValueModel->valueEncode($fieldValue['VALUE']);
            $fieldValue['ORDER_PROPS_ID'] = $propId;

            unset($fieldValue['ID']);

            $fieldValues[$propCode] = $fieldValue;
        }

        $updatedValues = array();
        $saleOrderUserPropertiesValue = new \CSaleOrderUserPropsValue;

        $userPropertiesList = $saleOrderUserPropertiesValue::GetList(
            array("SORT" => "ASC"),
            array("USER_PROPS_ID" => $this['ID']),
            false,
            false,
            array("ID", "ORDER_PROPS_ID", "VALUE", "SORT", "PROP_TYPE")
        );

        while ($propertyValues = $userPropertiesList->Fetch()) {
            if (array_key_exists($propertyValues["ORDER_PROPS_ID"], $fieldValues)) {
                \Bitrix\Sale\Internals\UserPropsValueTable::update(
                    $propertyValues["ID"],
                    array("VALUE" => $fieldValues[$propertyValues["ORDER_PROPS_ID"]]['VALUE'])
                );
            }
            $updatedValues[$propertyValues["ORDER_PROPS_ID"]] = $fieldValues[$propertyValues["ORDER_PROPS_ID"]];
        }

        if ($newValues = array_diff_key($fieldValues, $updatedValues)) {
            foreach ($newValues as $value) {
                unset($value['MULTIPLE']);
                $value['NAME'] = '';
                $saleOrderUserPropertiesValue->Add($value);
            }
        }

        $this->markRefetch();
    }

    function isDefault()
    {
        return $this->getPropValue('PROFILE_DEFAULT') === 'Y';
    }

    function prepare()
    {
        if (!trim($this->getPropValue('ADDRESS')) && $this->getPropValue('STREET_PATH')) {
            $parts = [];
            if ($this->getPropValue('STREET_PATH')) {
                $parts[] = $this->getPropValue('STREET_PATH');
            }
            if ($this->getPropValue('HOUSE')) {
                $parts[] = $this->getPropValue('HOUSE');
            }
            $address = join(', ', $parts);
            $this->setPropValue('ADDRESS', $address);
        }
    }

    function prepareForClient()
    {
        $this->prepare();
    }

    public function getCoordinates()
    {
        $coords = $this->getPropValue('HOUSE_COORDS') ?: $this->getPropValue('STREET_COORDS');
        if ($coords) {
            if (!is_array($coords)) {
                $coords = explode(':', $coords);
            }
        }
        return $coords;
    }

    function needFetchServiceData()
    {
        $freeFromPrice = intval($this->getPropValue('DELIVERY_FREE_FROM_PRICE'));
        $freeFromPriceUpdatedTime = $this->getPropValue('DELIVERY_FREE_UPDATED_TIME') ?: 0;
        $age = time() - $freeFromPriceUpdatedTime;
        return $freeFromPrice <= 0 || $age > 3600 * 24 * 365;
    }


    /* @var OrderAttributeCollection */
    public $attributes;

    /**
     * @return OrderAttributeCollection
     */
    public function getAttributes()
    {
        if (!isset($this->attributes)) {
            $propsValues = $this->getPropsValuesAll();
            $props = $this->getProfilePropsAll();

            $attrsService = $this->container->getOrderAttributesService();
            $attrs = new OrderAttributeCollection([]);

            foreach ($props as $prop) {
                $propValue = $propsValues[$prop['ID']];

                $attr = $attrsService->getAttribute($prop['CODE']);

                if (!$propValue) {
                    $attrValue = $attr->createValueEntity($prop['DEFAULT_VALUE']);
                } else {
                    $attrValue = $attr->createValueEntity($propValue->getValue());
                }

                $attr->setValueEntity($attrValue);

                $attrs[$prop['CODE']] = $attr;
            }

            $this->attributes = $attrs;
        }

        return $this->attributes;
    }
}


