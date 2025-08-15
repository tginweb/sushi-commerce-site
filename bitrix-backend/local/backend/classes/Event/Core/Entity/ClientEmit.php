<?php

namespace Event\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;
use Main\Lib\Date\DateTime;
use function TG\Event\Core\Entity\randString;

/**
 * @property string targetClientId
 * @property string targetUserId
 *
 * @property string title
 * @property string message
 * @property string body
 *
 * @property string eventName
 * @property mixed eventData
 * @property string eventGroup
 *
 * @property string entityType
 * @property string entityId
 *
 * @property string createdAt
 *
 */
class ClientEmit extends ArrayableModel
{
    use Containerable;

    public $notice = null;

    function __construct($fields = [])
    {
        $fields += [
            'eventData' => []
        ];
        $this->id = randString();
        $this->fields = $fields;
        $this->fillByType();
    }

    function fillByType()
    {

    }

    function getNotice()
    {
        return $this->notice;
    }

    function createRelated()
    {
        $noticeData = $this->createClientUserNotice();
        if ($noticeData) {
            if (is_array($noticeData)) {
                $notice = $this->container->getClientNoticeService()->createUserNotice($noticeData['eventName'], $noticeData, false);
            } else if (is_object($noticeData)) {
                $notice = $noticeData;
            }
            $notice->targetClientId = $this->targetClientId;
            $notice->targetUserId = $this->targetUserId;
            $notice->entityType = $this->entityType;
            $notice->entityId = $this->entityId;
            $notice->save();
            $notice->queueAdd();
            $this->notice = $notice;
        }
    }

    function createClientUserNotice()
    {
        return false;
    }

    function getEventData($prop = null)
    {
        return $prop ? $this['eventData'][$prop] : $this['eventData'];
    }

    function getDto()
    {
        $data = $this->toArray();
        $data['createdAt'] = DateTime::parseToTimestamp($this->createdAt);
        return $data;
    }

    function queueAdd()
    {
        $this->container->getClientEmitService()->addToQueue($this);
        return $this;
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

}

