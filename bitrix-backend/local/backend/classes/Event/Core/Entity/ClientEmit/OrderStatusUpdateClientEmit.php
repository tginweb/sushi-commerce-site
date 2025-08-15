<?php

namespace Event\Core\Entity\ClientEmit;

use Event\Core\Entity\ClientEmit;
use Shop\Core\Entity\OrderModel;

class OrderStatusUpdateClientEmit extends ClientEmit
{
    public $order;

    function fillByType()
    {
        $eventData = $this->eventData;
        $orderId = $this->entityId;
        $order = $this->getOrder();
        $this->entityType = 'order';
        $this->eventGroup = 'orderStatusUpdate:' . $orderId;
        $eventData['orderId'] = $orderId;
        if ($order) {
            $eventData['statusId'] = $order->getField('STATUS_ID');
            $eventData['orderPartial'] = $order->getStatusRelatedFields();
            $this->targetUserId = intval($order->getUserId());
            $this->targetClientId = $order->getPropValue('CODE', 'CLIENT_ID');
        }
        $this->eventData = $eventData;
    }

    function getOrder()
    {
        if (!isset($this->order)) {
            $this->order = OrderModel::load($this->entityId);
        }
        return $this->order;
    }

    function createClientUserNotice()
    {
        return [
            'eventName' => $this->eventName,
            'eventGroup' => $this->eventGroup,
            'eventData' => [
                'orderId' => $this->getEventData('orderId'),
                'statusId' => $this->getEventData('statusId'),
            ],
        ];
    }
}

