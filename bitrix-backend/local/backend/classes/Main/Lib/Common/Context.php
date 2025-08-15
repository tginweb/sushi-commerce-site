<?php

namespace Main\Lib\Common;

class Context
{
    var $params = [];

    public function getParam($name, $def = null)
    {
        return $this->params[$name] ?? $def;
    }

    public function setParams($params)
    {
        $this->params += $params;
    }
}
