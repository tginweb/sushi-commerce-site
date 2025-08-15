<?php

namespace Telegram\Core;

use Main\Service\BaseService;
use Telegram\Core\Entity\Chat;
use Telegram\Core\Entity\ChatMessage;

class Service extends BaseService
{
    public $messagesQueue;

    function isChatStarted($phone)
    {
        return !!Chat::query()->filter([
            'UF_CONTACT_PHONE' => $phone
        ])->first();
    }

    function sendMessageToPhone($phone, $message)
    {
        $chats = Chat::query()->filter([
            'UF_CONTACT_PHONE' => $phone
        ])->getList();

        if (!$chats->count())
            return false;

        /**
         * @var Chat $chat
         */
        foreach ($chats as $chat) {
            $chatMessage = new ChatMessage();
            $chatMessage
                ->setChatUid($chat->getUid())
                ->setChatId($chat->getChatId())
                ->setPhone($phone)
                ->setMessage($message);
            $this->addMessageToQueue($chatMessage);
        }

        $this->queueSend();
        return true;
    }

    function addMessageToQueue($chatMessage)
    {
        $this->messagesQueue[] = $chatMessage;
    }

    function queueSend()
    {
        $queueMessages = [];

        foreach ($this->messagesQueue as $chatMessage) {
            $queueMessages[] = $chatMessage->getTransportData();
        }

        if (count($queueMessages)) {
            $redis = $this->container->getRedisService()->getConnection();
            if ($redis) {
                $redis->publish('telegram:messages', json_encode($queueMessages));
            }
        }

        $this->messagesQueue = [];
    }

    function getChatByPhone($phone)
    {
        return Chat::query()->filter([
            'UF_CONTACT_PHONE' => $phone
        ])->first();
    }
}


