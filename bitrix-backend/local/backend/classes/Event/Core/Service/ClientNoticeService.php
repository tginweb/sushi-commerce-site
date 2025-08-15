<?php

namespace Event\Core\Service;

use Bitrix;
use Event\Core\Entity\ClientNotice;
use Event\Core\Entity\ClientNoticeRead;
use Main\Service\BaseService;
use function TG\Event\Core\Service\FormatDate;

class ClientNoticeService extends BaseService
{
    /**
     * @var ClientNotice[]
     */
    public $queue = [];
    public $config = [];
    public $targetIdByCode;
    public $showAsIdByCode;

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('CLIENT_NOTICE');
    }

    function getNoticeClass($eventName)
    {
        $types = $this->container->applyFiltersCached('hl-model-class:notice', []);
        return $types[$eventName] ?? ClientNotice::class;
    }

    /**
     * @return ClientNotice
     */
    function createNotice($eventName, $fields = [])
    {
        $cls = $this->getNoticeClass($eventName);
        $fields['eventName'] = $eventName;
        $event = new $cls(null, $fields);
        return $event;
    }

    function getTargetCodeById($id = null)
    {
        $targetCodeById = array_flip($this->getTargetIdByCode());
        return $id ? $targetCodeById[$id] : $targetCodeById;
    }

    function getTargetIdByCode($code = null)
    {
        if (!isset($this->targetIdByCode)) {
            global $USER_FIELD_MANAGER;
            $arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_26");
            $obEnum = new \CUserFieldEnum;
            $rsEnum = $obEnum->GetList(
                [],
                [
                    'USER_FIELD_ID' => $arFields['UF_TARGET']['ID']
                ]
            );
            $this->targetIdByCode = [];
            while ($arEnum = $rsEnum->Fetch()) {
                $this->targetIdByCode[$arEnum['XML_ID']] = $arEnum['ID'];
            }
        }
        return $code ? $this->targetIdByCode[$code] : $this->targetIdByCode;
    }

    function getShowAsCodeById($id = null)
    {
        $map = array_flip($this->getShowAsIdByCode());
        return $id ? $map[$id] : $map;
    }

    function getShowAsIdByCode($code = null)
    {
        if (!isset($this->showAsIdByCode)) {
            global $USER_FIELD_MANAGER;
            $arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_26");
            $obEnum = new \CUserFieldEnum;
            $rsEnum = $obEnum->GetList(
                [],
                [
                    'USER_FIELD_ID' => $arFields['UF_SHOW_AS']['ID']
                ]
            );
            $this->showAsIdByCode = [];
            while ($arEnum = $rsEnum->Fetch()) {
                $this->showAsIdByCode[$arEnum['XML_ID']] = $arEnum['ID'];
            }
        }
        return $code ? $this->showAsIdByCode[$code] : $this->showAsIdByCode;
    }

    function createUserNotice($eventName, $fields = [], $save = true)
    {
        $entity = $this->createNotice($eventName, $fields);
        $entity->target = $this->getTargetIdByCode('individual');
        $this->fillCommonData($entity);
        $this->fillUserData($entity);
        if ($save)
            $entity->save();
        return $entity;
    }

    function createCommonNotice($eventName, $fields = [])
    {
        $notice = $this->createNotice($eventName, $fields);
        $this->fillCommonData($notice);
        $notice->save();
        return $notice;
    }

    function addToQueue(ClientNotice $notice)
    {
        $this->queue[] = $notice;
    }

    function fillCommonData(&$entity)
    {
        $entity['createdAt'] = FormatDate('FULL', time());
        return $entity;
    }

    function fillUserData(&$entity)
    {
        $appClientService = $this->container->getAppClientService();
        $clientId = $appClientService->getClientId();
        $userId = intval($this->container->getUserId());

        if (!$entity['targetClientId'])
            $entity['targetClientId'] = $clientId;

        if (!$entity['targetUserId'])
            $entity['targetUserId'] = $userId;

        return $entity;
    }

    function fillBeforeSend(&$entity)
    {
        return $entity;
    }

    function onShutdown()
    {
        $this->queueCommit();
    }

    function queueCommit()
    {
        $items = [];

        foreach ($this->queue as $entity) {
            $this->fillBeforeSend($entity);
            $items[] = $entity->getDto();
        }

        $this->queue = [];

        if (!empty($items)) {
            $redis = $this->container->getRedisService()->getConnection();
            if ($redis)
                $redis->publish('client:notices', json_encode($items));
        }
    }

    function getCurrentUserNotices($limit = 50)
    {
        $userId = $this->container->getUserId();
        $clientId = $this->container->getAppClientService()->getClientId();
        return $this->getUserNotices($userId, $clientId, $limit);
    }

    function saveCurrentUserNoticesReaded($noticesIds = [])
    {
        $userId = $this->container->getUserId();
        $clientId = $this->container->getAppClientService()->getClientId();
        $this->saveUserNoticesReaded($userId, $clientId, $noticesIds);
    }

    function saveUserNoticesReaded($userId, $clientId, $noticesIds = [])
    {
        $savedReadedIds = $this->getUserNoticesReadedIds($userId, $clientId, $noticesIds);

        foreach ($noticesIds as $noticeId) {
            if (!$savedReadedIds[$noticeId]) {
                ClientNoticeRead::create([
                    'UF_NOTICE_ID' => $noticeId,
                    'UF_USER_ID' => $userId,
                    'UF_CLIENT_ID' => $clientId,
                ]);
            }
        }
    }

    function getUserNoticesReadedIds($userId, $clientId, $noticesIds = [])
    {
        if (!$userId && !$clientId || empty($noticesIds))
            return [];

        $filter = [
            'LOGIC' => 'OR',
        ];

        if ($userId)
            $filter['UF_USER_ID'] = $userId;
        else if ($clientId)
            $filter['UF_CLIENT_ID'] = $clientId;

        $noticesReaded = ClientNoticeRead::query()
            ->filter([
                'LOGIC' => 'AND',
                'UF_NOTICE_ID' => $noticesIds,
                $filter
            ])
            ->getList();

        $result = [];

        foreach ($noticesReaded as $item) {
            $result[$item['UF_NOTICE_ID']] = $item['UF_NOTICE_ID'];
        }

        return $result;
    }

    function getUserNotices($userId, $clientId = null, $limit = 50)
    {
        $filterIndividual = [];

        if ($userId) {
            $filterIndividual = [
                'LOGIC' => 'AND',
                'UF_TARGET' => $this->getTargetIdByCode('individual'),
                'UF_TARGET_USER_ID' => $userId
            ];
        } else if ($clientId) {
            $filterIndividual = [
                'LOGIC' => 'AND',
                'UF_TARGET' => $this->getTargetIdByCode('individual'),
                'UF_TARGET_CLIENT_ID' => $clientId
            ];
        } else {
            return [];
        }

        $filterCommon = [
            'LOGIC' => 'AND',
            'UF_TARGET' => $this->getTargetIdByCode('common'),
        ];

        $filter = [
            'LOGIC' => 'OR',
            $filterIndividual,
            $filterCommon
        ];

        $notices = ClientNotice::query()
            ->filter($filter)
            ->order('ID', 'DESC')
            ->limit($limit)
            ->getList();

        $result = [];

        $noticesIds = $notices->getIds();

        $readedIds = $this->getUserNoticesReadedIds($userId, $clientId, $noticesIds);
        //$readedIds = [];

        /**
         * @var ClientNotice[] $notices
         */
        foreach ($notices as $notice) {
            $notice->setIsReaded(!!$readedIds[$notice['ID']]);
            $result[] = $notice;
        }

        return $result;
    }
}



