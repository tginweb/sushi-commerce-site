<?php

namespace Event\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class EventSendStatusEnum extends BaseEnum
{
    static function options()
    {
        return [
            'none' => 'не отправлять',
            'queue' => 'В очереди',
            'sended' => 'Отправлено',
            'recipient_not_found' => 'Нет адресата',
            'error' => 'Ошибка',
        ];
    }
}
