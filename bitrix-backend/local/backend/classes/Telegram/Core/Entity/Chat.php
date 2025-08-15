<?php

namespace Telegram\Core\Entity;

use Main\Entity\D7\D7Model;

class Chat extends D7Model
{
    public static function tableName()
    {
        return 'telegram_chat';
    }

    public function getChatId()
    {
        return $this['UF_CHAT_ID'];
    }

    public function getUid()
    {
        return $this['UF_UID'];
    }
}
