<?php

namespace Event\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use Main\Lib\Date\DateTime;

/**
 * @property string target
 * @property string targetClientId
 * @property string targetUserId
 * @property string targetUserGroup
 *
 * @property string title
 * @property string message
 * @property string body
 * @property string bodyHtml
 * @property string image
 * @property string offerId
 *
 * @property string eventName
 * @property mixed eventData
 * @property string eventGroup
 *
 * @property string entityType
 * @property string entityId
 *
 * @property numeric sendPush
 * @property numeric sendPushCount
 *
 * @property numeric showAs
 *
 * @property string actionMobile
 * @property string actionWeb
 *
 * @property string createdAt
 */
class ClientNotice extends D7Model
{
    use Containerable;

    const DISCRIMINATOR_FIELD = 'UF_EVENT_NAME';

    public $jsonFields = [
        'eventData' => [],
        'actionMobile' => []
    ];

    const FIELDS_MAP = [
        'target' => 'UF_TARGET',
        'targetClientId' => 'UF_TARGET_CLIENT_ID',
        'targetUserId' => 'UF_TARGET_USER_ID',
        'targetUserGroup' => 'UF_TARGET_USER_GROUP',

        'title' => 'UF_TITLE',
        'message' => 'UF_MESSAGE',
        'body' => 'UF_BODY',

        'eventName' => 'UF_EVENT_NAME',
        'eventData' => 'UF_EVENT_DATA',
        'eventGroup' => 'UF_EVENT_GROUP',

        'entityType' => 'UF_ENTITY_TYPE',
        'entityId' => 'UF_ENTITY_ID',

        'createdAt' => 'UF_CREATED_AT',
        'sendPush' => 'UF_SEND_PUSH',
        'sendPushCount' => 'UF_SEND_PUSH_COUNT',

        'bodyHtml' => 'UF_BODY_HTML',
        'showAs' => 'UF_SHOW_AS',

        'actionMobile' => 'UF_ACTION_MOBILE',
        'actionWeb' => 'UF_ACTION_WEB',
    ];

    var $isReaded = false;

    function __construct($id = null, $fields = null)
    {
        parent::__construct($id, $fields);
        $this->fillByType();
    }

    function fillByType()
    {

    }

    public function getId()
    {
        return intval($this->id);
    }

    public function getEntityId()
    {
        return intval($this->entityId);
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getEventGroup()
    {
        return $this->eventGroup;
    }

    public function getEntityType()
    {
        return $this->entityType;
    }

    public static function tableName()
    {
        return 'notice';
    }

    function getEventData($prop = null)
    {
        return $prop ? $this['eventData'][$prop] : $this['eventData'];
    }


    function getImage()
    {
        return $this->image;
    }

    function getOfferId()
    {
        return $this->offerId;
    }

    function getHaveBody()
    {
        return $this->getBody() || $this->getBodyHtml();
    }

    function getTitle()
    {
        return $this->title;
    }

    function getMessage()
    {
        return $this->message;
    }

    function getBody()
    {
        return $this->body;
    }

    function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    function getActions($platform = 'web')
    {
        $actions = [];

        if ($this->getTargetCode() === 'common') {
            $actions[] = [
                'label' => 'подробнее',
                'url' => 'store://notice/showNoticeDialog?id=' . $this->getId(),
            ];
        }

        return $actions;
    }

    function getActions1($platform = 'web')
    {
        $res = [];
        $action = $platform === 'mobile' ? $this->actionMobile : $this->actionWeb;
        if ($action && count($action)) {
            $res[] = $action;
        }
        return $res;
    }

    function getActionsEmit($platform = 'web')
    {
        $res = [];
        return $res;
    }

    function setShowAsByCode($code)
    {
        $this->showAs = $this->container->getClientNoticeService()->getShowAsIdByCode($code);
    }

    function setTargetByCode($code)
    {
        $this->target = $this->container->getClientNoticeService()->getTargetIdByCode($code);
    }

    function getTargetCode()
    {
        return $this->target ? $this->container->getClientNoticeService()->getTargetCodeById($this->target) : null;
    }

    function getShowAs()
    {
        return $this->showAs ? $this->container->getClientNoticeService()->getShowAsCodeById($this->showAs) : null;
    }

    public function getCreatedAtTimestamp()
    {
        return DateTime::parseToTimestamp($this->createdAt);
    }

    public function getDto()
    {
        $data = $this->toArray();

        $data['id'] = $this->getId();
        $data['title'] = $this->getTitle();
        $data['message'] = $this->getMessage();
        $data['body'] = $this->getBody();
        $data['bodyHtml'] = $this->getBodyHtml();
        $data['haveBody'] = $this->getHaveBody();
        $data['image'] = $this->getImage();
        $data['createdAt'] = $this->getCreatedAtTimestamp();
        $data['targetCode'] = $this->getTargetCode();
        $data['actionsMobile'] = $this->getActions('mobile');

        $data['showAs'] = $this->getShowAs();
        $data['offerId'] = $this->getOfferId();

        return $data;
    }

    public function getPushData()
    {
        $data['title'] = $this->getTitle();
        $data['body'] = $this->getMessage();

        $payload = [
            'type' => 'notice',
            'id' => $this->getId(),
            'body' => $this->getBody(),
            'image' => $this->getImage(),
            'eventName' => $this->getEventName(),
            'eventData' => $this->getEventData(),
            'eventGroup' => $this->getEventGroup(),
            'entityId' => $this->getEntityId(),
            'entityType' => $this->getEntityType(),
            'targetCode' => $this->getTargetCode(),
            'createdAt' => $this->getCreatedAtTimestamp(),
            'actionUrlMobile' => $this->getActionUrl('mobile')
        ];

        $payloadJson = json_encode($payload);

        $payloadJsonLength = mb_strlen($payloadJson);

        if ($payloadJsonLength > 4000) {
            $payload['partial'] = true;
            unset($payload['image']);
        }

        $data['data'] = $payload;

        return $data;
    }

    public function queueAdd()
    {
        $this->container->getClientNoticeService()->addToQueue($this);
        return $this;
    }

    public function onBeforeSave()
    {
        if ($this->eventGroup) {
            $filter = [
                'UF_EVENT_GROUP' => $this->eventGroup
            ];
            if ($this->id) {
                $filter['!ID'] = $this->id;
            }
            $eventGroupOtherNotices = static::query()->filter($filter)->getList();
            foreach ($eventGroupOtherNotices as $notice) {
                if ($notice->id != $this->id)
                    $notice->delete();
            }
        }
        parent::onBeforeSave();
    }

    function setIsReaded($state = true)
    {
        $this->isReaded = $state;
    }

    function getIsReaded()
    {
        return $this->isReaded;
    }
}
