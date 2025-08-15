<?php

namespace Shop\Core\Service;

use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PaySystem\Manager;
use Main\Entity\Model\ModelCollection;
use Main\Service\BaseService;
use Shop\Core\Entity\OrderModel;
use TG\Shop\Core\Entity\Wrapper\Paysystem as PaysystemWrapper;

class PaymentService extends BaseService
{
    public $list = null;

    function getPaySystems()
    {
        if (!isset($this->list)) {
            $rs = Manager::getList();
            while ($item = $rs->fetch()) {
                $this->list[$item['ID']] = $item;
            }
        }
        return $this->list;
    }

    function getComputedPaySystems(OrderModel $order)
    {
        $collection = $order->getPaymentCollection();
        $extPayment = $collection->createItem();
        $extPayment->setField('SUM', $order->getPrice());
        $list = PaySystem\Manager::getListWithRestrictions($extPayment);
        $extPayment->delete();
        $result = [];
        foreach ($list as $item) {
            if ($item['ACTION_FILE'] !== 'inner') {
                $result[$item['ID']] = $item;
            }
        }
        return $result;
    }

    function ensurePayments(OrderModel $order)
    {
        if ($order->isCanPay()) {

            $delta = $order->getPrice() - $order->getPaymentCollection()->getSum();

            if ($delta > 0) {

                $order->getPaysystem();

                $payType = $order->getPayTypeWrapper();

                $paySystemId = $order->getField('PAY_SYSTEM_ID');

                $paymentCollection = $order->getPaymentCollection();

                $payment = $paymentCollection->createItem(PaySystem\Manager::getObjectById($paySystemId));

                $payment->setField('SUM', $delta);
                $payment->setField('CURRENCY', $order->getCurrency());

                $order->save();
            }
        }
    }

    function ensureOrderUnpaidOnlinePayment(OrderModel $order)
    {
        if (!$order->isCanPay())
            return;

        $paysystemId = $order->getDepartmentPaysystemId();

        if (!$paysystemId)
            return false;

        $bonusPayment = null;

        $unpaidPrice = $order->getTotalField('PRICE_TOTAL');

        foreach ($order->getPaymentCollection() as $payment) {

            if ($payment->isBonus()) {
                $unpaidPrice -= $payment->getSum();
                $bonusPayment = $payment;
                continue;
            }
            if (!$payment->isPaid()) {
                if ($payment->isOnline()) {
                    $order->setPropVal('PAYMENT_TYPE', 'online');
                    $order->save();
                    return $payment;
                } else {
                    $payment->delete();
                }
            } else {
                $unpaidPrice -= $payment->getSum();
            }
        }

        if ($unpaidPrice > 0) {

            $paymentCollection = $order->getPaymentCollection();

            $bonuses = $order->getBonuses();

            if ($bonuses && !$bonusPayment) {
                $bonusWrapper = \Shop\Core\Entity\Wrapper\Paysystem\Base::getByClass(\Shop\Core\Entity\Wrapper\Paysystem\Bonus::class);

                if ($bonusWrapper) {
                    $unpaidPrice -= $bonuses;
                    \CSaleUserAccount::UpdateAccount(
                        $order->getUserId(),
                        $bonuses,
                        $order->getCurrency(),
                        "Bonus",
                        $order->getId()
                    );

                    $bonusesPayment = $paymentCollection->createItem($bonusWrapper->getPaysystemObject());
                    $bonusesPayment->setField('SUM', $bonuses);
                    $bonusesPayment->setField('CURRENCY', $order->getCurrency());
                    $order->save();

                    $bonusesPayment->setPaid("Y");
                }
            }

            //$payType = $this->getPayTypeWrapper();

            $payment = $paymentCollection->createItem(PaySystem\Manager::getObjectById($paysystemId));
            $payment->setField('SUM', ceil($unpaidPrice));
            $payment->setField('CURRENCY', $order->getCurrency());

            $order->setPropVal('PAYMENT_TYPE', 'online');
            $order->save();

            return $payment;
        }
    }

    function getPaymentTypes()
    {
        return $this->container->cache(__FUNCTION__, function () {
            return (new ModelCollection([
                [
                    'CODE' => 'cash',
                    'ICON' => 'payment_cash',
                    'NAME' => 'Наличные',
                    'AUTHORIZED' => false,
                ],
                [
                    'CODE' => 'card',
                    'ICON' => 'payment_terminal',
                    'NAME' => 'Безналичные',
                    'AUTHORIZED' => false
                ],
                [
                    'CODE' => 'online',
                    'ICON' => 'payment_card',
                    'NAME' => 'Онлайн оплата',
                    'AUTHORIZED' => true
                ],
            ]))->pluck(null, 'CODE');
        });
    }
}


