<?php

namespace Main\Api\Controller;

use const TG\Main\Api\Controller\SITE_ID;

class TemplaterController extends BaseController
{
    function __construct()
    {
        $router = $this->container->getRouter();

        $router->get('template.render', '/main/template/render-event', [$this, 'templateEventRender']);
    }

    function templateEventRender()
    {
        $preprocessor = $_REQUEST['PREPROCESSOR'];
        $eventTypeName = $_REQUEST['EVENT_TYPE_NAME'];
        $eventId = $_REQUEST['EVENT_ID'];
        $entityId = $_REQUEST['ENTITY_ID'];

        if (!$preprocessor) {
            if (preg_match('/ORDER/', $eventTypeName)) {
                $preprocessor = 'order';
            }
        }

        $arFields = [
            'ENTITY_ID' => $entityId,
            'PREPROCESSOR' => $preprocessor,
            'PREVIEW_RENDER' => true
        ];

        if ($eventId) {
            $html = $this->container->getTemplaterService()->getEventHistoryPreview($eventId);
        } else {
            $html = $this->container->getTemplaterService()->getEventPreview($eventTypeName, SITE_ID, $arFields);
        }

        print $html;
        die();
    }
}
