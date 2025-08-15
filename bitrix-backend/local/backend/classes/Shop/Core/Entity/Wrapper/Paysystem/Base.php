<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

use Bitrix;
use Bitrix\Main\Application;
use Bitrix\Main\Error;
use Bitrix\Sale;
use Bitrix\Sale\PaySystem;
use Main\DI\Containerable;
use Shop\Core\Entity\OrderModel;
use TG\Main\Helper;

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
        $classSchema = self::container()->getConfigService()->get('SALE.PASYSTEM_WRAPPER_CLASS');

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
    static function wrap($paysystem)
    {
        //die(\Safe\json_encode($paysystem));

        if (is_object($paysystem)) {
            $id = $paysystem->getField('ID');
            $paysystemInstance = $paysystem;
        } else if (is_array($paysystem)) {
            $id = $paysystem['ID'];
            $paysystemInstance = PaySystem\Manager::getObjectById($id);;
        } else if (is_numeric($paysystem)) {
            $id = $paysystem;
            $paysystemInstance = PaySystem\Manager::getObjectById($id);;
        }

        if (!$paysystemInstance) {
            return false;
        }

        if (!isset(self::$_instances[$id])) {

            $classSchema = self::container()->getConfigService()->get('SALE.PASYSTEM_WRAPPER_CLASS');

            if ($classSchema[$id]) {
                $wrapperClass = $classSchema[$id];
                if (class_exists($wrapperClass))
                    $wrapper = new $wrapperClass($id, $paysystemInstance);
            }

            if (!$wrapper) {
                $wrapper = new static($id, null);
            }

            self::$_instances[$id] = $wrapper;
        }

        return self::$_instances[$id];
    }

    function getPaysystemObject()
    {
        return $this->host;
    }

    /**
     * @return bool
     */
    public function isInner()
    {
        return (int)$this->id === (int)Sale\PaySystem\Manager::getInnerPaySystemId();
    }

    function isOnline()
    {
        return false;
    }

    function isOnlineDelayed()
    {
        return false;
    }

    function isBill()
    {
        return false;
    }

    function isBonus()
    {
        return false;
    }

    function supportOnlineRecurrentPayment()
    {
        return false;
    }

    function getPaymentLink($payment, $options = [])
    {
        return \Main\Helper\Str::makeUrl('/served/neat/payment/yookassa/initiate.php', [
            'ORDER_ID' => $payment->getOrderId(),
            'PAYMENT_ID' => $payment->getId(),
            'hash' => $payment->getHash()
        ], $options);
    }

    function getPayNav($payment)
    {

    }

    function getActionMobile($payment)
    {
        return null;
    }

    function getActionWeb($payment)
    {
        return null;
    }

    function getPsStatusId($payment)
    {

        switch ($payment->getField('PS_STATUS')) {
            case 'Y':
                $id = 'succeed';
                break;
            case 'N':
                $id = 'canceled';
                break;
        }

        return $id;
    }

    function processResult($data)
    {
        $GLOBALS['PHP_INPUT_HACK'] = json_encode($data);
        $GLOBALS['PAYMENT_IP_CHECK_DISABLE'] = true;

        $context = Application::getInstance()->getContext();

        $service = PaySystem\Manager::getObjectById($this->getPaysystemId());

        $result = new Bitrix\Sale\Result();

        if ($service) {
            $result = $service->processRequest($context->getRequest());
        } else {
            $result->addError(new Error('Платежная система не найдена'));
        }

        return $result;
    }

    function getPaysystemId()
    {
        return $this->id;
    }

    function loadOrder($id)
    {
        if ($id) {
            return is_object($id) ? $id : OrderModel::query()->getById($id);
        }
    }

    public function confirmOrder($id)
    {

    }

    function getMergedFields()
    {
        return $this->host + [

            ];
    }
}


