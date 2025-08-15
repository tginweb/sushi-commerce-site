<?php

namespace Event\Core\Entity\ClientNotice;

use Event\Core\Entity\ClientNotice;
use Shop\Core\Entity\OrderModel;

class OrderStatusUpdateClientNotice extends ClientNotice
{
    function fillByType()
    {
        $this->sendPush = 1;
        $this->setShowAsByCode('alert');
    }

    function getStatus()
    {
        $statusId = $this->getEventData('statusId');
        if ($statusId)
            return OrderModel::statusInfo($statusId);
    }

    function getTitle()
    {
        $statusId = $this->getEventData('statusId');
        $status = $this->getStatus();
        if ($statusId === 'P') {
            $name = 'Оформляется';
        } else {
            $name = $status['NAME'];
        }
        return $status ? 'Новый статус вашего заказа: "' . $name . '"' : null;
    }

    function getMessage()
    {
        return '';
    }

    function getBody()
    {
    }

    function isFinished()
    {
        $statusId = $this->getEventData('statusId');

        return $statusId === 'F';
    }

    function getActions($platform = 'web')
    {
        $eventData = $this->getEventData();

        if (!$this->isFinished()) {
            if ($platform === 'mobile') {
                $action = [
                    'label' => 'перейти к заказу',
                    'roles' => ['startupRun'],
                    'action' => [
                        'url' => 'router://user/order?orderId=' . $eventData['orderId']
                    ]
                ];
            } else {
                $action = [
                    'label' => 'перейти к заказу',
                    'roles' => ['startupRun', 'list'],
                    'url' => '/personal/order?orderId=' . $eventData['orderId']
                ];
            }
            return [$action];
        } else {
            return [];
        }
    }
}
