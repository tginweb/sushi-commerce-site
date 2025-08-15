<?php

namespace Main\Entity\Logger;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;

class LoggerEvent extends ArrayableModel
{
    public $service;
    public $parent;
    public $withApiResult;

    use Containerable;

    function __construct($id = null, $fields = [], $parent = null)
    {
        $fields += [
            '_id' => new \MongoDB\BSON\ObjectId(),
            'depth' => 0,
            'context' => [
                '_h' => true
            ],
            'data' => [
                '_h' => true
            ],
            'request' => [
                '_h' => true
            ],
            'response' => [
                '_h' => true
            ],
            'sess' => [
                '_h' => true
            ],
            'app' => [
                '_h' => true
            ],
            'created' => intval(microtime(true) * 1000),
            'ended' => intval(microtime(true) * 1000),
        ];

        $this->parent = $parent;
    }

    public function deepSet($field, $value)
    {
        return $this->fields;
    }

    public function getContext()
    {
        return $this['context'] ?? [];
    }

    public function setContext($context)
    {
        $this['context'] = $context;
        return $this;
    }

    public function setType($type)
    {
        $this['type'] = $type;
    }

    public function setResponseData($data)
    {
        $this->fieldDeepSet('response.data', $data);
    }

    public function setResponseErrors($errors)
    {
        $this->fieldDeepSet('response.errors', $errors);
    }

    public function end()
    {
        $this['ended'] = intval(microtime(true) * 1000);
        return $this;
    }

    public function setApiResult($result)
    {
        $this->fieldDeepSet('request.result', $result);
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function child($newFields = [])
    {
        $newFields += [
            'parent' => $this['_id'],
            'depth' => $this['depth'] + 1
        ];
        $newFields['context'] = $this->getContextExtended($newFields['context'] ?? []);
        $newFields['app'] = $this['app'];
        $child = new static(null, $newFields, $this);
        return $child;
    }

    public function getContextExtended($context = [])
    {
        $context = ($context ?? []) + ($this['context'] ?? []);
        return $context;
    }

    public function setFields($fields = [])
    {
        $keys = [
            'type',
            'message',
            'name',
            'code',
            'data',
            'app',
            'request',
            'response',
            'sess',
            'log',
        ];
        foreach ($keys as $key) {
            if (isset($fields[$key])) {
                $this->fields[$key] = $fields[$key];
            }
        }
        if (isset($fields['context']))
            $this->addContext($fields['context']);
        return $this;
    }

    public function queueAdd()
    {
        $this->container->getLoggerService()->addToQueue($this);
        return $this;
    }

    public function getParentsIds()
    {
        $parents = [];
        $parent = $this;
        while ($parent = $parent->getParent()) {
            $parents[] = $parent['_id']->__toString();
        }
        return $parents;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent()
    {
        $this->container->getEventService()->setParentEvent($this);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/^log(.+)/', $name, $mt)) {

            $logEventType = strtolower($mt[1]);
            $args = $arguments;
            array_unshift($args, $logEventType);

            return call_user_func_array([$this, 'log'], $arguments);
        }
        return parent::__call($name, $arguments);
    }

    public function getSaveData()
    {
        $result = [];

        $this['timer'] = $this['ended'] - $this['created'];

        $data = $this->fields;

        foreach ($data as $key => $val) {

            /*
            if (is_array($val) && !count($val)) {
                $val = new \stdClass();
            }
            */

            $result[$key] = $val;
        }

        $result['_id'] = $this['_id']->__toString();

        if ($this['parent'])
            $result['parent'] = $this['parent']->__toString();

        $result['parents'] = $this->getParentsIds();

        return $result;
    }

    public function withResponseData($val = null)
    {
        if (isset($val)) {
            $this->withApiResult = $val;
        }

        return $this->withApiResult;
    }

    function getAdminActions($viewmode = null, $user = null)
    {
        $result = [];

        $result[] = [
            'icon' => 'visibility',
            'key' => 'view',
            'label' => 'Просмотр',
            'listEvent' => 'open',
            'rowRoot' => true,
            'command' => [
                'type' => 'router',
                'path' => '/admin/logger/event/' . $this['ID']
            ],
        ];

        return $result;
    }

}

