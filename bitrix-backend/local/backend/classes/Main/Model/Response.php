<?php

namespace Main\Model;

use Main\DI\Containerable;
use Main\Error\BaseError;
use Throwable;

class Response extends Result
{
    use Containerable;

    public $rate = [
        'limited' => false,
        'ttl' => 0
    ];

    public $action;
    public $messages = [];
    public $redirect = null;

    public $errorMessagesGroup = [
        'id' => null,
        'errorTypes' => null,
        'errorNames' => null,
    ];

    function getErrorsJson($transportType)
    {
        return $this->errors->map(function (Throwable $error) {
            if ($error instanceof BaseError) {
                $data = $error->getJson();
            } else {
                $data = [
                    'type' => 'common',
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ];
            }
            return $data;
        });
    }

    function getRestJson()
    {
        $errors = $this->getErrorsJson('rest');
        return [
            'state' => $this->getState(),
            'success' => $this->getSuccess(),
            'errors' => $errors,
            'error' => $errors[0],
            'payload' => $this->payload,
            'data' => $this->data,
        ];
    }

    function getGraphqlJson()
    {
        $errors = $this->getErrorsJson('graphql');
        return [
            'state' => $this->getState(),
            'success' => $this->getSuccess(),
            'errors' => $errors,
            'error' => $errors[0],
            'payload' => $this->payload,
            'data' => $this->data,
        ];
    }

    function getState()
    {
        return [
            'messages' => $this->messages,
            'events' => $this->events,
            'action' => $this->action,
            'rate' => $this->rate,
            'redirect' => $this->redirect,
        ];
    }

    function setRateLimit($rateTtl, $limited = false)
    {
        $this->rate['limited'] = $limited;
        $this->rate['ttl'] = $rateTtl;
        return $this;
    }

    function emitEntityUpdated($entity)
    {
        return $this->emitEntity('changed', $entity);
    }

    function emitEntity($op, $entity, $entityId = null)
    {
        // updated,created,deleted | changed

        $args = [];

        if (is_object($entity)) {

            if (method_exists($entity, 'getEntityType')) {
                $entityType = $entity->getEntityType();
            } else if ($entity::ENTITY_TYPE) {
                $entityType = $entity::ENTITY_TYPE;
            }

            if (!$entityId) {
                if (method_exists($entity, 'getId')) {
                    $entityId = $entity->getId();
                } else if ($entity->id) {
                    $entityId = $entity->id;
                }
            }
        } else if (is_string($entity)) {
            $entityType = $entity;
        }

        $args += [
            'entityType' => $entityType,
            'entityId' => intval($entityId),
            'op' => $op
        ];

        $events = [];

        $events[] = 'entity.' . $op . ':' . $entityType . '.' . $entityId;
        $events[] = 'entity.' . $op . ':' . $entityType;

        $commonEvent = 'changed';

        $events[] = 'entity.' . $commonEvent . ':' . $entityType . '.' . $entityId;
        $events[] = 'entity.' . $commonEvent . ':' . $entityType;

        foreach ($events as $event) {
            $this->emit($event, $args);
        }

        return $this;
    }

    function emit($name, $args = [])
    {
        $this->events[] = [
            'name' => $name,
            'args' => $args
        ];
        return $this;
    }

    function addException($e)
    {
        $this->addError($e);
        return $this;
    }

    function addError(BaseError $error)
    {
        if ($this->errorMessagesGroup['id']) {
            if (
                (empty($this->errorMessagesGroup['errorTypes']) || in_array($error->getType(), $this->errorMessagesGroup['errorTypes'] ?: []))
                &&
                (empty($this->errorMessagesGroup['errorNames']) || in_array($error->getName(), $this->errorMessagesGroup['errorNames'] ?: []))
            ) {
                $error->setMessageGroup($this->errorMessagesGroup['id']);
            }
        }
        parent::addError($error);
        return $this;
    }

    function addSuccess($message = '', $info = [])
    {
        $message = (array)(is_string($message) ? ['message' => $message] : $message) + $info;
        $message['type'] = 'success';
        $this->addMessage($message);
        return $this;
    }

    function addMessage($message)
    {
        $type = $message['type'];
        switch ($type) {
            case 'success':
                if (!isset($this->success))
                    $this->setSuccess();
                break;
            case 'error':
                $this->setFail();
                break;
        }
        $this->_addMessage($message);
        return $this;
    }

    function _addMessage(&$message)
    {
        $message += [
            'id' => uniqid(),
            'actions' => [],
        ];
        $this->messages[] = $message;
    }

    function addWarning($message = '', $info = [])
    {
        $message = (array)(is_string($message) ? ['message' => $message] : $message) + $info;
        $message['type'] = 'warning';
        $this->addMessage($message);
        return $this;
    }

    function addInfo($message = '', $info = [])
    {
        $message = (array)(is_string($message) ? ['message' => $message] : $message) + $info;
        $message['type'] = 'info';
        $this->addMessage($message);
        return $this;
    }

    function addMessages($messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }

    function haveMessagesByType($type)
    {
        return count($this->getMessagesByType($type)) > 0;
    }

    function getMessagesByType($type)
    {
        return array_filter($this->messages, function ($message) use ($type) {
            return $message['type'] === $type;
        });
    }

    function getMessages($criteria = [])
    {
        return $this->messages;
    }

    function startErrorMessagesGroup($errorTypes = [], $errorNames = [])
    {
        $this->errorMessagesGroup = [
            'id' => uniqid(),
            'errorTypes' => !empty($errorTypes) ? (is_array($errorTypes) ? $errorTypes : [$errorTypes]) : [],
            'errorNames' => !empty($errorNames) ? (is_array($errorNames) ? $errorNames : [$errorNames]) : [],
        ];
    }

    function endErrorMessagesGroup()
    {
        $this->errorMessagesGroup = [];
    }

    function setRedirect($info)
    {
        $data = [
            'native' => isset($info['native']) ? $info['native'] : false
        ];
        if (!isset($info['fullPath'])) {
            $data['fullPath'] = $data['path'];
        } else {
            $data['fullPath'] = $info['path'];
        }
        $this->redirect = $info;
        return $this;
    }

    function commit()
    {
        $this->addMessages($this->errors->getMessages());
    }

    function haveSuccessMessages()
    {
        return $this->haveMessagesByType('success');
    }
}
