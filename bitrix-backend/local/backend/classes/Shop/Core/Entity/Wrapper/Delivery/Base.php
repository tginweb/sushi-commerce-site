<?php

namespace Shop\Core\Entity\Wrapper\Delivery;

use Bitrix;
use Main\DI\Containerable;

class Base
{
    use Containerable;

    static $_instances = [];
    public $id;
    public $host;

    function __construct($id, $host)
    {
        $this->id = $id;
        $this->host = $host;
    }

    /**
     * @return self
     */
    static function getByClass($class)
    {
        $classSchema = self::container()->getConfigService()->get('SALE.DELIVERY_WRAPPER_CLASS');

        foreach ($classSchema as $itemId => $itemClass) {
            if ($class == $itemClass) {
                $id = $itemId;
            }
        }

        if ($id) {
            return static::wrap($id);
        }
    }

    /**
     * @return self
     */
    static function wrap($delivery, $create = false)
    {
        if (is_array($delivery)) {
            $id = $delivery['ID'];
            $deliveryInstance = $delivery;
        } else if (is_array($delivery)) {
            $id = $delivery['ID'];
            $deliveryInstance = \Bitrix\Sale\Delivery\Services\Manager::getById($id);
        } else if (is_numeric($delivery)) {
            $id = $delivery;
            $deliveryInstance = \Bitrix\Sale\Delivery\Services\Manager::getById($id);
        }

        if (!$deliveryInstance) {
            return false;
        }

        if (!isset(self::$_instances[$id]) || $create) {

            $classSchema = self::container()->getConfigService()->get('SALE.DELIVERY_WRAPPER_CLASS');
            $wrapperClass = $classSchema[$id];

            if ($wrapperClass && class_exists($wrapperClass)) {
                $obj = new $wrapperClass($id, $deliveryInstance);
            } else {
                $obj = !$create && self::$_instances['default'] ? self::$_instances['default'] : new static($id, $deliveryInstance);
                $id = 'default';
            }

            if ($create) {
                return $obj;
            } else {
                self::$_instances[$id] = $obj;
                return $obj;
            }
        }

        return self::$_instances[$id];
    }

    function getDeliveryId()
    {
        return $this->id;
    }

    function getMergedFields()
    {
        return $this->host + [
                'TRANSPORT_TYPE' => $this->getTransportType(),
            ];
    }

    function getTransportType()
    {
        return '';
    }
}


