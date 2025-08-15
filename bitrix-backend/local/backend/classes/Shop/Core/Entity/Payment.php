<?php

namespace Shop\Core\Entity;

use Bitrix\Sale;
use Bitrix\Sale\PaySystem;

class Payment extends Sale\Payment
{
    public $extPaySystem;
    public $hash;

    function loadPaySystem()
    {
        $paysystemId = $this->getPaymentSystemId();

        if ($paysystemId) {
            return PaySystem\Manager::getObjectById($paysystemId);
        }
    }

    function getPaySystem()
    {
        if (!isset($this->extPaySystem)) {
            $this->extPaySystem = $this->loadPaySystem();
        }
        return $this->extPaySystem;
    }

    function getPaySystemName()
    {
        if ($paysystem = $this->getPaySystem()) {
            return $paysystem->getField('NAME');
        }
    }

    public function isOnline()
    {
        return $this->getPaysystemWrapper()->isOnline();
    }

    public function isBill()
    {
        return $this->getPaysystemWrapper()->isBill();
    }

    public function isBonus()
    {
        return $this->getPaysystemWrapper()->isBonus();
    }

    function initOnlinePayment()
    {
        $paySystemId = $this->getPaymentSystemId();

        if (!$this->isInner() && !$this->isPaid()) {

            $context = \Bitrix\Main\Application::getInstance()->getContext();

            $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($paySystemId);

            $service->initiatePay($this, $context->getRequest());

            $initResult = $service->initiatePay($this, $context->getRequest(), \Bitrix\Sale\PaySystem\BaseServiceHandler::STRING);

            $buffered_output = $initResult->getTemplate();
        }
    }

    function getPayCommand($action = false)
    {
        $wrapper = $this->getPaysystemWrapper();
        return $wrapper->getPayNav($this);
    }

    function getPaysystemWrapper()
    {
        $wrapper = \Shop\Core\Entity\Wrapper\Paysystem\Base::wrap($this->getPaymentSystemId());

        return $wrapper;
    }

    function getHashCached($refetch = false)
    {
        if (!isset($this->hash) || $refetch) {
            $this->hash = $this->getHash();
        }
        return $this->hash;
    }

    function getRestrictedUrl($params = [], $options = [])
    {
        return $this->getOrder()->getGuestUrl(['paymentId' => $this->getId()]);
    }

    static function getById($id)
    {
        $payments = \Bitrix\Sale\Payment::getList([
            'filter' => [
                'ID' => $id
            ]
        ]);

        $paymentRow = $payments->fetch();

        if ($paymentRow && $paymentRow['ORDER_ID']) {

            $order = OrderModel::query()->filter(['ID' => $paymentRow['ORDER_ID']])->first();

            if ($order) {
                foreach ($order->getPaymentCollection() as $payment) {
                    if ($payment->getId() == $id)
                        return $payment;
                }
            }
        }
    }

    function getPaysystemStatusId()
    {
        return $this->getPaysystemWrapper()->getPsStatusId($this);
    }

    function getPaysystemStatusName()
    {
        $id = $this->getPaysystemWrapper()->getPsStatusId($this);

        $statuses = [
            'succeed' => 'успешно оплачено',
            'canceled' => 'отмена оплаты',
            'holded' => 'оплата заморожена',
            'pending' => 'ожидание оплаты',
        ];

        return $statuses[$id];
    }
}


