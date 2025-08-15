<?php

namespace Event\Core;

use Event\Core\Entity\ClientEmit\OrderStatusUpdateClientEmit;
use Event\Core\Entity\ClientEmit\ProfileFillGiftClientEmit;
use Event\Core\Entity\ClientNotice\OrderStatusUpdateClientNotice;
use Event\Core\Entity\ClientNotice\ProfileFillGiftClientNotice;
use Event\Core\Enum\EventSendStatusEnum;
use Event\Core\Enum\NoticeShowAsEnum;
use Event\Core\Enum\NoticeTypeEnum;
use Event\Core\Service\ClientEmitService;
use Event\Core\Service\ClientNoticeService;
use Event\Core\Service\ClientPushService;
use Main\Graphql\Types;
use Main\Lib\Common\BaseComponent;
use Main\Service\LoggerService;

class Module extends BaseComponent
{

    function register($scopes = [])
    {

        $clientPushService = $this->container->register(ClientPushService::class);
        $clientNoticeService = $this->container->register(ClientNoticeService::class);
        $clientEmitService = $this->container->register(ClientEmitService::class);
        $loggerService = $this->container->register(LoggerService::class);

        register_shutdown_function([$clientNoticeService, 'onShutdown']);
        register_shutdown_function([$clientEmitService, 'onShutdown']);
        register_shutdown_function([$clientPushService, 'onShutdown']);

        //register_shutdown_function([$loggerService, 'onShutdown']);

        $this->container->addFilter('hl-model-class:notice', function ($data) {
            $data['orderStatusUpdate'] = OrderStatusUpdateClientNotice::class;
            $data['profileFillGift'] = ProfileFillGiftClientNotice::class;
            return $data;
        });

        $this->container->addFilter('client_emit:types', function ($data) {
            $data['orderStatusUpdate'] = OrderStatusUpdateClientEmit::class;
            $data['profileFillGift'] = ProfileFillGiftClientEmit::class;
            return $data;
        });

        parent::register($scopes);
    }

    function registerTypes()
    {
        Types::types([
            'ClientNotice' => \Event\Core\Graphql\ClientNoticeType::class,
            'ClientEvent' => \Event\Core\Graphql\ClientEmitType::class,
        ]);

        Types::enums([
            'EventSendStatusEnum' => EventSendStatusEnum::class,
            'NoticeShowAsEnum' => NoticeShowAsEnum::class,
            'NoticeTypeEnum' => NoticeTypeEnum::class,
        ]);
    }
}
