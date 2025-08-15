<?php

namespace Mail\Core;

use Main\Service\BaseService;
use const TG\Mail\Core\SITE_ID;

class Service extends BaseService
{
    function OnBeforePhpMail($event) {

        $args = $event->getParameter("arguments");

        if ($this->container()->getConfigService()->get('MAIL.LIMIT_RECIPIENT_ENABLE')) {

            $limitAddress = $this->container()->getConfigService()->get('MAIL.LIMIT_RECIPIENT_ADDRESS');

            if (!empty($limitAddress)) {
                if (!in_array($args->to, $limitAddress)) {
                    $args->to = 'not_existing_email_address@not_existing_domain.com';
                }
            }
        }
    }

    function onBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
    {
        if (isset($arFields['PREPROCESSOR'])) {

            $preprocessor = $arFields['PREPROCESSOR'];

            $arFields = $this->getHooks()->apply_filters_ref_array('mail:event.preprocessor.' . $preprocessor, [&$arFields, &$lid]);
        }
    }

    function eventSend($eventName, $arFields = [], $siteId = null, $immediate = false) {
        $siteId = $siteId ?: SITE_ID ?: 's1';

        if ($immediate || $this->container->getDebugService()->isDebugRequest()) {
            \CEvent::SendImmediate($eventName, $siteId, $arFields);
        } else {
            \CEvent::Send($eventName, $siteId, $arFields);
        }
    }
}


