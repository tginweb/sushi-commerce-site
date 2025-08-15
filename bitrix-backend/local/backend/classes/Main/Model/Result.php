<?php

namespace Main\Model;

use Main\DI\Containerable;
use Main\Error\BaseError;
use Main\Error\ErrorCollection;

class Result
{
    use Containerable;

    public ErrorCollection $errors;
    public $success = null;

    public $payload;
    public $data = [];
    public $events = [];

    function __construct()
    {
        $this->errors = new ErrorCollection();
    }

    function addException($e)
    {
        $this->addError($e);
        return $this;
    }

    function addError(BaseError $error)
    {
        $this->setFail();
        $this->errors->push($error);
        /*
        if ($error instanceof BaseError) {
            if ($error->haveMessage()) {
                $this->addMessage($error->getMessageFields());
            }
        }
        */
        return $this;
    }

    function addErrors($errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    function haveErrors()
    {
        return !$this->errors->isEmpty();
    }

    function getSuccess()
    {
        return $this->success;
    }

    function isSuccess()
    {
        return $this->success;
    }

    function setFail()
    {
        $this->success = false;
        return $this;
    }

    function setSuccess()
    {
        $this->success = true;
        return $this;
    }

    function setData($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    function setPayloadData($name, $value)
    {
        $this->payload[$name] = $value;
        return $this;
    }

    function assignPayload($payload)
    {
        if (!$this->payload) {
            $this->payload = [];
        }
        $this->payload += $payload;
        return $this;
    }

    function getPayload()
    {
        return $this->payload;
    }

    function setPayload($payload)
    {
        $this->payload = $payload;
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
}
