<?php

namespace Event\Core\Entity\ClientEmit;

use Event\Core\Entity\ClientEmit;

class ProfileFillGiftClientEmit extends ClientEmit
{
    public $order;

    function fillByType()
    {
        $eventData = $this->eventData;
        $this->eventData = $eventData;
    }

    function createClientUserNotice()
    {
        return [
            'eventName' => $this->eventName,
            'eventData' => [
                'bonuses' => $this->getEventData('bonuses'),
            ],
        ];
    }
}

