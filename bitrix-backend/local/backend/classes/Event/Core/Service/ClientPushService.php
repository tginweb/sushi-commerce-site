<?php

namespace Event\Core\Service;

use Bitrix;
use Curl\Curl;
use Event\Core\Entity\ClientNotice;
use Main\Entity\User\AppClientModel;
use Main\Service\BaseService;
use function TG\Event\Core\Service\AddMessage2Log;

class ClientPushService extends BaseService
{
    const SEND_NOTICE_LIMIT = 100;
    const PUSH_PACKET_LIMIT = 100;

    function onShutdown()
    {
        $this->queueSend();
    }

    function sendPushes($pushes)
    {
        $curl = new Curl();

        $curl->setDefaultJsonDecoder(true);
        $curl->setHeader('Content-Type', 'application/json');
        $url = 'https://exp.host/--/api/v2/push/send';

        $res = $curl->post($url, $pushes);

        AddMessage2Log(['sendPushes' => $pushes]);
        AddMessage2Log(['response' => $res]);

    }

    function createPushFromNotice($pushToken, ClientNotice $notice)
    {
        $push = [
            'to' => $pushToken
        ];
        $push = $push + $notice->getPushData();
        return $push;
    }

    function queueSend()
    {
        AddMessage2Log('PUSH - queueSend');

        $notices = $this->container->getClientPushService()->getNoticeQueueItems(static::SEND_NOTICE_LIMIT);


        AddMessage2Log(['$notices' => $notices]);

        $pushes = [];

        foreach ($notices as $notice) {

            $appClients = $this->getNoticePushSubscribers($notice);

            AddMessage2Log(['$appClients' => $appClients]);

            if (count($appClients)) {
                foreach ($appClients as $appClient) {
                    $pushToken = $appClient->getPushToken();

                    if ($pushToken) {
                        $pushes[] = $this->createPushFromNotice($pushToken, $notice);
                        if (count($pushes) > static::PUSH_PACKET_LIMIT - 1)
                            break;
                    }
                }
                $notice->sendPushCount = $notice->sendPushCount + count($appClients);
            } else {
                $notice->sendPushCount = -1;
            }

            $notice->save();
        }

        if (!empty($pushes)) {
            $this->sendPushes($pushes);
        }
    }

    /**
     * @return ClientNotice[]
     */
    function getNoticeQueueItems($count = 20)
    {
        return [];
        return ClientNotice::query()
            ->filter([
                'UF_SEND_PUSH' => 1,
                'UF_SEND_PUSH_COUNT' => 0,
            ])
            ->limit($count)
            ->getList()
            ->all();
    }

    /**
     * @return AppClientModel[]
     */
    function getNoticePushSubscribers(ClientNotice $notice)
    {
        $filter = [
            '!UF_PUSH_TOKEN' => ''
        ];

        if ($notice->targetUserId) {
            $filter['UF_USER_ID'] = $notice->targetUserId;
        } else if ($notice->targetClientId) {
            $filter['UF_CLIENT_ID'] = $notice->targetClientId;
        } else {
            return [];
        }

        return AppClientModel::query()->filter($filter)->getList()->all();
    }

}



