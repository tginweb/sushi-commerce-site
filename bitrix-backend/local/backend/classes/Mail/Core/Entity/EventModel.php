<?php

namespace Mail\Core\Entity;

use Bitrix\Main\Mail\Internal\EventTable;
use Main\Entity\D7\D7Model;

class EventModel extends D7Model
{
    public $eventFields = null;

    public static function getFieldsInfo()
    {
        return [];
    }

    public static function tableClass()
    {
        return EventTable::class;
    }

    public function getField($name)
    {
        return $this['C_FIELDS'][$name];
    }

    public function getOrderId()
    {
        return $this->getField('ORDER_REAL_ID');
    }

    public function getUserId()
    {
        $userId = $this->getField('USER_ID');
        return $userId;
    }

    function getAdminActions($viewmode = null, $user = null)
    {

        $result = [];

        $result[] = [
            'id' => 'view',
            'icon' => 'visibility',
            'label' => 'Просмотр',
            'listEvent' => 'open',
            'rowRoot' => true,
            'command' => [
                'type' => 'router',
                'path' => '/admin/mail/event/' . $this['ID']
            ],
        ];

        $result[] = [
            'id' => 'delete',
            'icon' => 'delete',
            'group' => true,
            'label' => 'Удалить',
            'command' => [
                'type' => 'dispatch',
                'path' => 'mail_admin/eventAction',
                'params' => [
                    'action' => 'delete'
                ],
                'confirm' => 'подтвердите удаление',
            ],
        ];

        return $result;
    }
}
