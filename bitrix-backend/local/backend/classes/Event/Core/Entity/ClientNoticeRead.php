<?php

namespace Event\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;

/**
 * @property numeric noticeId
 * @property numeric userId
 * @property string clientId
 */
class ClientNoticeRead extends D7Model
{
    use Containerable;

    const FIELDS_MAP = [
        'noticeId' => 'UF_NOTICE_ID',
        'userId' => 'UF_USER_ID',
        'clientId' => 'UF_CLIENT_ID',
    ];

    public static function tableName()
    {
        return 'notice_read';
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getNoticeId()
    {
        return $this->noticeId;
    }
}
