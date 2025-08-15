<?php

namespace Shop\Pub\Api\Resolver;

use Main\Error\CommonError;
use Main\Graphql\Generator\EntityResolversGenerator;
use Main\Graphql\Type\Action\ActionWebType;
use Main\Graphql\Types;
use Main\Model\Response;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Graphql\OrderFilterType;
use Shop\Core\Graphql\OrderType;
use Shop\Core\Graphql\PaymentType;

class OrderResolver extends EntityResolversGenerator
{
    public $ns = 'sale_order_';

    public string $modelClass = OrderModel::class;

    public string $modelTypeClass = OrderType::class;
    public string $filterTypeClass = OrderFilterType::class;

    function getQueryMap()
    {
        return [
            'active_list' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull($this->modelTypeClass)),
                    'args' => $this->getQueryListArgs(Types::get($this->filterTypeClass)),
                    'resolve' => [$this, 'queryActive'],
                ];
            },
            'ensure_payment' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(PaymentType::class),
                    'args' => [
                        'id' => Types::int(),
                        'hash' => Types::string(),
                        'paymentId' => Types::int()
                    ],
                    'resolve' => [$this, 'queryEnsurePayment'],
                ];
            },
        ];
    }

    function getMutationMap()
    {
        return [
            'pay_online' => function ($queryName, $queryParams) {
                return [
                    'payload' => [
                        'action' => Types::get(ActionWebType::class),
                        'order' => Types::get($this->modelTypeClass),
                    ],
                    'args' => [
                        'id' => Types::int(),
                        'hash' => Types::string(),
                        'type' => Types::string(),
                        'savePaymentType' => Types::boolean(),
                    ],
                    'resolve' => [$this, 'mutationPaymentOnline'],
                ];
            },
            'repeat' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::get(OrderType::class),
                    'args' => [
                        'id' => Types::int(),
                        'hash' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutateRepeat'],
                    'mode' => 'user'
                ];
            },
            'cancel' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::listOf(Types::getNonNull(OrderType::class)),
                    'args' => [
                        'id' => Types::int(),
                        'hash' => Types::string(),
                        'reason' => Types::string(),
                        'comment' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutateCancel']
                ];
            },
        ];
    }

    function mutationPaymentOnline($rootValue, $args, $ctx, $info, Response $response)
    {
        $order = $this->getGuestOrUserOrder($args);

        if (!$order) {
            $response->addError('Заказ не найден', ['notify' => false]);
        } else if (!$order->isCanPayOnline()) {
            $response->addError('Заказ не может быть оплачен онлайн', ['notify' => false]);
        } else {
            $payment = $this->container->getPaymentService()->ensureOrderUnpaidOnlinePayment($order);
            if ($payment) {
                $wrapper = $payment->getPaysystemWrapper();
                $response->setPayloadData('action', $wrapper->getActionWeb($payment));
            } else {
                $response->addError('Онлайн-оплата по данному поздразделению временно недоступна', ['notify' => false]);
            }
        }
    }

    /**
     * @return OrderModel
     */
    function getGuestOrUserOrder($args)
    {
        $userId = $this->container->getUserId();

        $order = OrderModel::query()->filter([
            'ID' => $args['id'],
        ])->first();

        if ($order) {
            if ($args['hash']) {
                if ($order->getAccessHash(true) == $args['hash']) {
                    return $order;
                }
            } else if ($order->getUserId() == $userId) {
                return $order;
            }
        }
    }

    function querySingle($rootValue, $args, $ctx)
    {
        $order = $this->getGuestOrUserOrder($args);
        return $order;
    }

    function queryList($rootValue, $args, $ctx)
    {
        $filter = $this->container->getOrderService()->getGuestOrUserFilter($args);
        unset($args['filter']['MODE']);
        return OrderModel::query()->filter($filter)->setClientNav($args['nav'])->sort('DATE_INSERT', 'DESC')->getList();
    }

    function queryRecordset($rootValue, $args, $ctx)
    {
        $filter = $this->container->getOrderService()->getGuestOrUserFilter($args);
        unset($args['filter']['MODE']);
        return OrderModel::query()->filter($filter)->setClientQuery($args)->sort('DATE_INSERT', 'DESC')->getGraph();
    }

    function queryActive($rootValue, $args, $ctx)
    {
        return $this->container->getOrderService()->getActiveOrders();
    }

    function queryEnsurePayment($rootValue, $args)
    {
        $order = $this->getGuestOrUserOrder($args);

        $paymentToPay = null;

        if ($order && $order->isCanPay()) {

            $paymentId = $args['paymentId'];

            $this->container->getPaymentService()->ensurePayments($order);

            $payments = $order->getPaymentsUnpaid();

            if (!empty($payments)) {
                if ($paymentId) {
                    foreach ($payments as $payment) {
                        if ($payment->getId() == $paymentId) {
                            $paymentToPay = $payment;
                            break;
                        }
                    }
                } else {
                    $paymentToPay = current($payments);
                }
            }
        }

        return $paymentToPay;
    }

    function mutateCancel($rootValue, $args, $ctx, $info, Response $response)
    {
        $order = $this->getGuestOrUserOrder($args);

        $comment = $args['comment'];
        $reasonCode = $args['reason'];

        foreach ($order->getCancelReasons() as $reason) {
            if ($reason['CODE'] === $reasonCode) {
                $comment = $reason['NAME'] . ': ' . $comment;
            }
        }

        if ($order) {
            $order->setField('REASON_CANCELED', $comment);
            $r = $order->setField('ACCOUNT_NUMBER', '99');
            if ($order->getField('CANCELED') === 'Y') {
                $r = $order->setField('CANCELED', 'N');
            } else {
                $r = $order->setField('CANCELED', 'N');
            }
            if ($r->isSuccess()) {
                $r = $order->save();
                if ($r->isSuccess()) {
                    $response->addSuccess('Заказ успешно отменен', ['notify' => true]);
                    $response->setPayload($this->container->getOrderService()->getActiveOrders());
                }
            }
        }

        if (!$response->isSuccess()) {
            $response->addError(new CommonError(null, 'Не удалось отменить заказ'));
        }
    }

    function mutateRepeat($rootValue, $args, $ctx, $info, Response $response)
    {
        $order = $this->getGuestOrUserOrder($args);

        if ($order) {
            $basketService = $this->container->getBasketService();
            $basketService->mutationAppendBasket($order->getBasket());
            $response->addSuccess('Заказ успешно повторен', ['notify' => true]);
        } else {
            $response->addSuccess('Заказ не найден', ['notify' => true]);
        }

        $response->setPayload($order);
    }
}



