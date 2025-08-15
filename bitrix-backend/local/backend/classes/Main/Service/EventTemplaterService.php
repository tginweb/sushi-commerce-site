<?php

namespace Main\Service;

use Bitrix\Main\EventManager;
use Bitrix\Main\Mail\Context;
use Bitrix\Main\Mail\EventMessageCompiler;
use Bitrix\Main\Mail\Internal as MailInternal;
use Bitrix\Main\Mail\Internal\EventTable;
use Bitrix\Main\Mail\StopException;
use Bitrix\Main\SiteTable;
use Exception;
use Main\Model\EventTemplate;
use function TG\Main\Service\ExecuteModuleEventEx;
use function TG\Main\Service\GetModuleEvents;
use function TG\Main\Service\GetTime;
use const TG\Main\Service\LANGUAGE_ID;

class EventTemplaterService extends BaseService
{
    const SEND_RESULT_TEMPLATE_NOT_FOUND = '0';

    public $eventTemplates = [];
    public $templates = [];

    function register($scopes = [])
    {
        $events = EventManager::getInstance();

        //OnBeforeEventSend

        $events->addEventHandler("main", "OnBeforeEventAdd", function (&$eventName, &$lid, &$arFields, &$eventMessageId, &$files, &$languageId) {
            $this->container->getTemplaterService()->onBeforeEventAdd($eventName, $lid, $arFields, $eventMessageId, $files, $languageId);
        });
    }

    function onBeforeEventAdd(&$eventName, &$lid, &$arFields, &$eventMessageId, &$files, &$languageId)
    {
        $template = $this->createEventTemplate($eventName, $eventMessageId);

        if ($template) {
            $template->initFromEvent($eventName, $arFields);
            $arFields = $template->getEventFieldsExport();
        }
    }

    /**
     * @return EventTemplate
     */
    function createEventTemplate($eventName, $messageId = null)
    {
        $templateCode = $this->getEventTemplateCode($eventName, $messageId);

        if ($templateCode) {
            return $this->createTemplate($templateCode);
        }
    }

    function getEventTemplateCode($eventName, $eventMessageId = null)
    {
        if ($eventMessageId) {
            if (isset($this->eventTemplates[$eventName][$eventMessageId])) {
                return $this->eventTemplates[$eventName][$eventMessageId];
            } else if (isset($this->eventTemplates[$eventName]['ALL'])) {
                return $this->eventTemplates[$eventName]['ALL'];
            }
        } else if ($this->eventTemplates[$eventName]['ALL']) {
            return $this->eventTemplates[$eventName]['ALL'];
        }
    }

    function createTemplate($code, $args = [])
    {
        $result = null;

        $info = $this->getTemplateInfo($code);

        if ($info) {
            if ($info['CLASS']) {
                $result = new $info['CLASS']($args);
            }
        }

        return $result;
    }

    function getTemplateInfo($code)
    {
        return $this->templates[$code];
    }

    function onBeforeEventSend(&$arFields, &$eventMessage, $context, &$arResult)
    {
        $eventName = $eventMessage['EVENT_NAME'];
        $eventMessageId = $eventMessage['ID'];
        $template = $this->createEventTemplate($eventName, $eventMessageId);
        if ($template) {
            $template->initFromEvent($eventName, $arFields);
            $arFields = $template->getEventFieldsExport();
        }
    }

    function registerTemplate($code, $class, $attachToEvent = false)
    {
        $this->templates[$code] = [
            'CLASS' => $class
        ];

        if ($attachToEvent) {
            $event = is_string($attachToEvent) ? $attachToEvent : $code;
            $this->setEventTemplate($code, $event);
        }
    }

    function setEventTemplate($event, $templateCode, $messageIds = [])
    {
        if (!empty($messageIds)) {
            $messageIds = (array)$messageIds;
            foreach ($messageIds as $messageId) {
                $this->eventTemplates[$event][$messageId] = $templateCode;
            }
        } else {
            $this->eventTemplates[$event]['ALL'] = $templateCode;
        }
    }

    function renderCodeTemplate($code, $args = [])
    {

    }


    function getSystemEventTemplate($event)
    {

    }

    /**
     * Show preview of content.
     *
     * @param array $params Parameters.
     * @return string
     */
    public function getEventPreview($event, $lid, $arFields, $Duplicate = "Y", $message_id = "", $files = array(), $languageId = '')
    {
        foreach (GetModuleEvents("main", "OnBeforeEventAdd", true) as $arEvent) {
            ExecuteModuleEventEx($arEvent, array(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId));
        }

        if (!is_array($arFields)) {
            $arFields = array();
        }

        $arEvent = array(
            "EVENT_NAME" => $event,
            "C_FIELDS" => $arFields,
            "LID" => (is_array($lid) ? implode(",", $lid) : $lid),
            "DUPLICATE" => ($Duplicate != "N" ? "Y" : "N"),
            "MESSAGE_ID" => (intval($message_id) > 0 ? intval($message_id) : ""),
            "DATE_INSERT" => GetTime(time(), "FULL"),
            "FILE" => $files,
            "LANGUAGE_ID" => ($languageId == '' ? LANGUAGE_ID : $languageId),
            "ID" => "0",
        );

        if (!isset($arEvent['FIELDS']) && isset($arEvent['C_FIELDS']))
            $arEvent['FIELDS'] = $arEvent['C_FIELDS'];

        $flag = static::SEND_RESULT_TEMPLATE_NOT_FOUND; // no templates


        $arResult = array(
            "Success" => false,
            "Fail" => false,
            "Was" => false,
            "Skip" => false,
        );

        $trackRead = null;
        $trackClick = null;
        if (array_key_exists('TRACK_READ', $arEvent))
            $trackRead = $arEvent['TRACK_READ'];
        if (array_key_exists('TRACK_CLICK', $arEvent))
            $trackClick = $arEvent['TRACK_CLICK'];

        $arSites = explode(",", $arEvent["LID"]);
        if (empty($arSites)) {
            return $flag;
        }

        // get charset and server name for languages of event
        // actually it's one of the sites (let it be the first one)
        $charset = false;
        $serverName = null;

        static $sites = array();
        $infoSite = reset($arSites);

        if (!isset($sites[$infoSite])) {
            $siteDb = SiteTable::getList(array(
                'select' => array('SERVER_NAME', 'CULTURE_CHARSET' => 'CULTURE.CHARSET'),
                'filter' => array('=LID' => $infoSite)
            ));
            $sites[$infoSite] = $siteDb->fetch();
        }


        if (is_array($sites[$infoSite])) {
            $charset = $sites[$infoSite]['CULTURE_CHARSET'];
            $serverName = $sites[$infoSite]['SERVER_NAME'];
        }

        if (!$charset) {
            return $flag;
        }


        // get filter for list of message templates
        $arEventMessageFilter = array();

        $MESSAGE_ID = intval($arEvent["MESSAGE_ID"]);

        if ($MESSAGE_ID > 0) {
            $eventMessageDb = MailInternal\EventMessageTable::getById($MESSAGE_ID);
            if ($eventMessageDb->Fetch()) {
                $arEventMessageFilter['=ID'] = $MESSAGE_ID;
                $arEventMessageFilter['=ACTIVE'] = 'Y';
            }
        }

        if (empty($arEventMessageFilter)) {

            $arEventMessageFilter = array(
                '=ACTIVE' => 'Y',
                '=EVENT_NAME' => $arEvent["EVENT_NAME"],
                '=EVENT_MESSAGE_SITE.SITE_ID' => $arSites,
            );

            if ($arEvent["LANGUAGE_ID"] <> '') {
                $arEventMessageFilter[] = array(
                    "LOGIC" => "OR",
                    array("=LANGUAGE_ID" => $arEvent["LANGUAGE_ID"]),
                    array("=LANGUAGE_ID" => null),
                );
            }
        }

        // get list of message templates of event
        $messageDb = MailInternal\EventMessageTable::getList(array(
            'select' => array('ID'),
            'filter' => $arEventMessageFilter,
            'group' => array('ID')
        ));

        while ($arMessage = $messageDb->fetch()) {

            $eventMessage = MailInternal\EventMessageTable::getRowById($arMessage['ID']);

            $eventMessage['FILES'] = array();
            $attachmentDb = MailInternal\EventMessageAttachmentTable::getList(array(
                'select' => array('FILE_ID'),
                'filter' => array('=EVENT_MESSAGE_ID' => $arMessage['ID']),
            ));

            while ($arAttachmentDb = $attachmentDb->fetch()) {
                $eventMessage['FILE'][] = $arAttachmentDb['FILE_ID'];
            }

            $context = new Context();
            $arFields = $arEvent['FIELDS'];

            foreach (GetModuleEvents("main", "OnBeforeEventSend", true) as $event) {
                if (ExecuteModuleEventEx($event, array(&$arFields, &$eventMessage, $context, &$arResult)) === false) {
                    continue 2;
                }
            }


            // get message object for send mail
            $arMessageParams = array(
                'EVENT' => $arEvent,
                'FIELDS' => $arFields,
                'MESSAGE' => $eventMessage,
                'SITE' => $arSites,
                'CHARSET' => $charset,
            );

            $message = EventMessageCompiler::createInstance($arMessageParams);

            try {
                $message->compile();

                return $message->getMailBody();
            } catch (StopException $e) {

            }
        }

    }

    /**
     * Show preview of content.
     *
     * @param array $params Parameters.
     * @return string
     */
    public function getEventHistoryPreview($eventId)
    {
        $rsEvents = EventTable::getList(array(
            'filter' => [
                'ID' => $eventId
            ],
            'select' => array(
                '*'
            ),
        ));

        $arEvent = $rsEvents->fetch();

        if (!$arEvent) {
            throw new Exception('Событие не найдено');
        }

        $flag = static::SEND_RESULT_TEMPLATE_NOT_FOUND; // no templates

        $arResult = array(
            "Success" => false,
            "Fail" => false,
            "Was" => false,
            "Skip" => false,
        );

        $arSites = explode(",", $arEvent["LID"]);
        if (empty($arSites)) {
            return $flag;
        }

        $charset = false;

        static $sites = array();
        $infoSite = reset($arSites);

        if (!isset($sites[$infoSite])) {
            $siteDb = SiteTable::getList(array(
                'select' => array('SERVER_NAME', 'CULTURE_CHARSET' => 'CULTURE.CHARSET'),
                'filter' => array('=LID' => $infoSite)
            ));
            $sites[$infoSite] = $siteDb->fetch();
        }

        if (is_array($sites[$infoSite])) {
            $charset = $sites[$infoSite]['CULTURE_CHARSET'];
        }

        if (!$charset) {
            return $flag;
        }

        $arEventMessageFilter = array();

        if ($arEvent['MESSAGE_ID'] > 0) {
            $eventMessageDb = MailInternal\EventMessageTable::getById($arEvent['MESSAGE_ID']);
            if ($eventMessageDb->Fetch()) {
                $arEventMessageFilter['=ID'] = $arEvent['MESSAGE_ID'];
                $arEventMessageFilter['=ACTIVE'] = 'Y';
            }
        }

        if (empty($arEventMessageFilter)) {
            $arEventMessageFilter = array(
                '=ACTIVE' => 'Y',
                '=EVENT_NAME' => $arEvent["EVENT_NAME"],
                '=EVENT_MESSAGE_SITE.SITE_ID' => $arSites,
            );

            if ($arEvent["LANGUAGE_ID"] <> '') {
                $arEventMessageFilter[] = array(
                    "LOGIC" => "OR",
                    array("=LANGUAGE_ID" => $arEvent["LANGUAGE_ID"]),
                    array("=LANGUAGE_ID" => null),
                );
            }
        }

        // get list of message templates of event
        $messageDb = MailInternal\EventMessageTable::getList(array(
            'select' => array('ID'),
            'filter' => $arEventMessageFilter,
            'group' => array('ID')
        ));

        while ($arMessage = $messageDb->fetch()) {

            $eventMessage = MailInternal\EventMessageTable::getRowById($arMessage['ID']);

            $eventMessage['FILES'] = array();
            $attachmentDb = MailInternal\EventMessageAttachmentTable::getList(array(
                'select' => array('FILE_ID'),
                'filter' => array('=EVENT_MESSAGE_ID' => $arMessage['ID']),
            ));

            while ($arAttachmentDb = $attachmentDb->fetch()) {
                $eventMessage['FILE'][] = $arAttachmentDb['FILE_ID'];
            }

            $context = new Context();
            $arFields = $arEvent['C_FIELDS'];

            foreach (GetModuleEvents("main", "OnBeforeEventSend", true) as $event) {
                if (ExecuteModuleEventEx($event, array(&$arFields, &$eventMessage, $context, &$arResult)) === false) {
                    continue 2;
                }
            }

            // get message object for send mail
            $arMessageParams = array(
                'EVENT' => $arEvent,
                'FIELDS' => $arFields,
                'MESSAGE' => $eventMessage,
                'SITE' => $arSites,
                'CHARSET' => $charset,
            );

            $message = EventMessageCompiler::createInstance($arMessageParams);

            try {
                $message->compile();

                return $message->getMailBody();
            } catch (StopException $e) {

            }
        }

    }
}


