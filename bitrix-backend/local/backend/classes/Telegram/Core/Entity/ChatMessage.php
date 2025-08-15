<?php

namespace Telegram\Core\Entity;

use Main\Entity\Model\ArrayableModel;

class ChatMessage extends ArrayableModel
{
    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    function setPhone($phone)
    {
        $this['phone'] = $phone;
        return $this;
    }

    function getPhone()
    {
        return $this['phone'];
    }

    function getChatUid()
    {
        return $this['chatUid'];
    }

    function setChatUid($chatUid)
    {
        $this['chatUid'] = $chatUid;
        return $this;
    }

    function setChatId($chatId)
    {
        $this['chatId'] = $chatId;
        return $this;
    }

    function getChatId()
    {
        return $this['chatId'];
    }

    function setMessage($message)
    {
        $this['message'] = $message;
        return $this;
    }

    function getMessage()
    {
        return $this['message'];
    }

    function getTransportData()
    {
        return [
            'chatUid' => $this->getChatUid(),
            'chatId' => $this->getChatId(),
            'phone' => $this->getPhone(),
            'message' => $this->getMessage(),
        ];
    }
}
