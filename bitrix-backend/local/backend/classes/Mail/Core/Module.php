<?php

namespace Mail\Core;

use Bitrix\Main\EventManager;
use Main\Graphql\Types;
use Main\Lib\Common\BaseComponent;

class Module extends BaseComponent
{
    function register($scopes = [])
    {
        $container = $this->container;

        $container->define(Service::class);

        $events = EventManager::getInstance();

        $events->addEventHandler("main", "OnBeforeEventAdd", function (&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId) {
            $this->container->getMailService()->onBeforeEventAdd($event, $lid, $arFields, $message_id, $files, $languageId);
        });

        $events->addEventHandler('main', 'OnBeforePhpMail', function ($event) {
            $this->container->getMailService()->OnBeforePhpMail($event);
        });

        new \Mail\Core\Dataloader\EventTypeLoader();

        parent::register();
    }

    function registerTypes()
    {
        Types::type('MailEventType', \Mail\Core\Graphql\EventTypeType::class);
        Types::type('MailEvent', \Mail\Core\Graphql\EventType::class);
        Types::type('MailEventConnection', \Mail\Core\Graphql\EventConnectionType::class);
    }
}



