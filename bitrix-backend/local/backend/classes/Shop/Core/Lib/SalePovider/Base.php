<?php

namespace Shop\Core\Lib\SalePovider;

use Bitrix\Main;
use Main\DI\Containerable;

class Base
{
    use Containerable;

    public $mixins = [];

    function __construct()
    {
    }

    function mixins($list = null)
    {
        if ($list) {
            $list = is_array($list) ? $list : [$list];
            $this->mixins = array_merge($this->mixins, $list);
            return $this;
        } else {
            return $this->mixins;
        }
    }

    function mixinsEvent($eventName, $event)
    {
        $product = $event->getParameter('PRODUCT');

        if (method_exists($product, $eventName)) {
            $r = $product->{$eventName}($event);
            if ($r && ($r->getType() == Main\EventResult::ERROR)) {
                return $r;
            }
        }

        foreach ($this->mixins as $mixin) {
            if (method_exists($mixin, $eventName)) {
                $r = $mixin::$eventName($event);
                if ($r && ($r->getType() == Main\EventResult::ERROR)) {
                    return $r;
                }
            }
        }
    }
}
