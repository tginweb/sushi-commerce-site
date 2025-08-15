<?php

namespace Shop\Core\Service;

use Bitrix;
use Main\Service\BaseService;
use Shop\Core\Entity\OrderAttributeCollection;
use Shop\Core\Entity\OrderField;
use Shop\Core\Entity\OrderFieldCollection;
use Shop\Core\Entity\OrderProperty;
use Shop\Core\Entity\OrderPropertyCollection;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributesService extends BaseService
{
    /** @var OrderPropertyCollection */
    public $props;

    /** @var OrderPropertyCollection */
    public $propsById;

    /** @var OrderFieldCollection */
    public $fields;

    /** @var OrderAttributeCollection */
    public $attributes;

    function register($scopes = [])
    {
    }

    /**
     * @return IOrderAttribute
     */
    function getAttribute($code)
    {
        $this->getAttributes();
        return $code ? $this->attributes[$code] : null;
    }

    /**
     * @return OrderAttributeCollection
     */
    function getAttributes($refetch = false)
    {
        if (!isset($this->attributes)) {
            $this->attributes = new OrderAttributeCollection(array_merge(
                $this->getProps($refetch)->all(),
                $this->getFields()->all()
            ));
        }
        return $this->attributes;
    }

    /**
     * @return OrderFieldCollection
     */
    function getFields()
    {
        if (!isset($this->fields)) {
            $this->fields = (new OrderFieldCollection([
                new OrderField\DeliveryId([
                    'TYPE' => 'number',
                    'CODE' => 'DELIVERY_ID',
                    'NAME' => 'Способ доставки',
                ]),
                new \Shop\Core\Entity\OrderField\PaySystemId([
                    'TYPE' => 'number',
                    'CODE' => 'PAY_SYSTEM_ID',
                    'NAME' => 'Способ оплаты',
                ]),
                new OrderField([
                    'TYPE' => 'string',
                    'CODE' => 'USER_DESCRIPTION',
                    'NAME' => 'Комменатрий',
                ]),
            ]))->pluck(null, 'CODE');
        }
        return $this->fields;
    }

    /**
     * @return OrderPropertyCollection
     */
    public function getProps($refetch = false)
    {
        if (!isset($this->props) || $refetch) {
            $this->props = OrderProperty::query()->sort('SORT', 'ASC')->getList()->pluck(null, 'CODE');
            $this->propsById = $this->props->pluck(null, 'ID');
        }
        return $this->props;
    }

    /**
     * @return OrderProperty
     */
    public function getProp($code)
    {
        if ($code) {
            $this->getProps();
            if (is_numeric($code)) {
                return $this->propsById[$code] ?? null;
            } else {
                return $this->props[$code] ?? null;
            }
        }
    }

    function getCodes()
    {
        return array_filter($this->getAttributes()->pluckArray('CODE', 'CODE'));
    }

    function getKinds()
    {
        return array_filter($this->getAttributes()->map(function (IOrderAttribute $attr) {
            return $attr->getAttrKind();
        })->toArrayValues());
    }

    function getTypes()
    {
        return array_filter($this->getAttributes()->map(function (IOrderAttribute $attr) {
            return $attr->getAttrType();
        })->toArrayValues());
    }
}



