<?php

namespace Main\Model;

use Main\DI\Containerable;
use Main\Traits\EventTemplate\Mail;
use Main\Traits\EventTemplate\Shop;

class EventTemplate
{
    use Containerable;
    use Mail;
    use Shop;

    public $event;
    public $eventFields = [];

    public $fields = [];
    public $computed;

    public function __contstruct($fields = [])
    {
        $this->fields = $fields;
    }

    public function initFromEvent($event, $eventFields = [])
    {
        $this->event = $event;
        $this->eventFields = $eventFields;
        $this->initFields($eventFields);
    }

    public function initFields($fields)
    {

    }

    public function getEventFieldsExport()
    {
        return $this->getFieldsExport() + $this->eventFields;
    }

    public function getFieldsExport()
    {
        return [

        ];
    }


    function getSocialsData($codes = [])
    {
        $socials = [
            [
                'URL_SETTING' => 'UF_SOCIAL_VK',
                'ICON_IMAGE' => '/upload/social-icon/VK.png',
            ],
            [
                'URL_SETTING' => 'UF_SOCIAL_INSTAGRAM',
                'ICON_IMAGE' => '/upload/social-icon/Instagram.png',
            ],
            [
                'URL' => 'https://api.whatsapp.com/send?phone=~VALUE~',
                'VALUE_SETTING' => 'UF_COMPANY_PHONE_WHATSAPP',
                'ICON_IMAGE' => '/upload/social-icon/WhatsApp.png',
            ]
        ];

        $items = [];

        foreach ($socials as $item) {
            if (isset($item['URL'])) {
                $itemUrl = $item['URL'];
                $itemValue = null;
                if (isset($item['VALUE_SETTING'])) {
                    $itemValue = $this->container->getSettingsService()->get($item['VALUE_SETTING']);
                }
                $itemUrl = str_replace('~VALUE~', $itemValue, $itemUrl);
            } else {
                $itemUrl = $this->container->getSettingsService()->get($item['URL_SETTING']);
            }
            $items[] = ['URL' => $itemUrl] + $item;
        }

        return $items;
    }

    function renderTemplate()
    {
        $template = $this->getTemplate();

        $this->compileTemplate($template);
    }

    function getTemplate()
    {
        return null;
    }

    function compileTemplate($code)
    {
        $this->getComputedInfo();

        return preg_replace_callback('/\~(\w+?\:)?(.+?)\~/', function ($mt) {
            $key = $mt[2];
            if (isset($this->computed[$key])) {
                return is_callable($this->computed[$key]) ? $this->computed[$key]() : $this->computed[$key];
            }
            return $this->getField($key);
        }, $code);
    }

    function getComputedInfo()
    {
        if (!isset($this->computed))
            $this->computed = $this->getComputed();
        return $this->computed;
    }

    function getComputed()
    {
        return [];
    }

    function getField($name)
    {
        return $this->fields[$name] ?? null;
    }

}
