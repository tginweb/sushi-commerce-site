<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Main\DI\Containerable;
use Main\Entity\D7\D7Adapter;
use Main\Entity\Model\Traits\ArrayAccessTrait;
use Shop\Core\Lib\IOrderAttribute;

class OrderProperty extends Sale\Property implements \ArrayAccess, IOrderAttribute
{
    use Containerable;
    use OrderAttributeTrait;
    use ArrayAccessTrait;

    static $adapter;

    public $params;
    public $role;

    public static function query()
    {
        return new OrderPropertyQuery(static::instantiateAdapter(), OrderProperty::class, OrderPropertyCollection::class);
    }

    public static function instantiateAdapter()
    {
        if (isset(static::$adapter))
            return static::$adapter;

        return static::$adapter = new D7Adapter(\Bitrix\Sale\Internals\OrderPropsTable::class);
    }

    public function getCode()
    {
        return $this->getField('CODE');
    }

    public function getEnumOptions($value = null, $order = null)
    {
        return [];
    }

    public function getParams()
    {
        if (!isset($this->params)) {
            $desc = trim($this->getDescription());
            $this->params = [];
            if ($desc && $desc[0] === '{') {
                $arrDesc = json_decode($desc, true);
                if (is_array($arrDesc)) {
                    $this->params = $arrDesc;
                }
            }
        }
        return $this->params;
    }

    public function getParam($name, $def = null)
    {
        $this->getParams();
        return $this->params[$name] ?? $def;
    }

    public function getRole()
    {
        if (!isset($this->role)) {
            $code = $this->getField('CODE');

            foreach (['LOCATION', 'EMAIL', 'LOCATION4TAX', 'ZIP', 'PHONE', 'ADDRESS', 'COORDINATES', 'DATE', 'TIME', 'DATETIME'] as $roleName) {
                if ($this->getField('IS_' . $roleName) === 'Y')
                    return $roleName;
                if ($code === $roleName)
                    return $roleName;
            }

            $this->role = '';
        }
        return $this->role;
    }

    public function getDesc()
    {
        $desc = parent::getDescription();
        if (trim($desc)[0] === '{') {
            return $this->getParam('DESC');
        }
    }

    public function getClientComponentNames()
    {
        $result = [];

        if ($paramComponent = $this->getParam('COMPONENT')) {
            $result[] = $paramComponent;
        }

        if ($role = $this->getRole()) {
            $result[] = 'SaleOrderProp' . ucfirst(strtolower($role));
        }

        $result[] = 'SaleOrderProp' . ucfirst(strtolower(preg_replace('/[^A-Za-z]/', '', $this->getType())));

        return $result;
    }

    function isMultiple()
    {
        return $this->getField('MULTIPLE') === 'Y';
    }

    function isProfile()
    {
        return $this->getField('USER_PROPS') === 'Y';
    }

    function isClientReadonly()
    {
        return $this->isUtil();
    }

    public function vorderUpdateFromClientAllow()
    {
        return true;
    }

    public function vorderUpdateFromClient(VorderCurrent $vorder, $value, $valueChanged = null, &$inputAttrsByType = [], $op = null)
    {
        if ($this->vorderUpdateFromClientAllow()) {
            $vorder->setPropValue('ID', $this->getId(), $value);
        }
    }

    public function getAttrKind()
    {
        return 'prop';
    }

    public function getAttrType()
    {
        switch ($this->getField('TYPE')) {
            case 'Y/N':
                $type = 'boolean';
                break;
            default:
                $type = $this->getField('TYPE');
        }
        return strtolower($type);
    }

    public function getDescription()
    {

    }

    public function getDefaultValue()
    {
        return $this->prepareValue($this->getField('DEFAULT_VALUE'));
    }
}


