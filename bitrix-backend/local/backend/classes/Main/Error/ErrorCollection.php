<?php

namespace Main\Error;

use Main\Entity\Model\ModelCollection;

class ErrorCollection extends ModelCollection
{

    function haveErrors($type = null, $name = null)
    {
        $filter = [];
        if ($type)
            $filter['type'] = $type;
        if ($name)
            $filter['name'] = $name;
        return !!$this->firstWhere($filter);
    }

    function getFieldsMapJoined($field, $delim = ', ')
    {
        $raw = $this->toFields();
        return join($delim, $raw->pluckArray($field, $field));
    }

    function toFields()
    {
        return $this->map(function ($item) {
            return $item->fields;
        });
    }

    function getMessages()
    {
        $messages = [];

        $errorsGrouped = [];
        $errorsNotGrouped = [];

        /**  @var BaseError $error */
        foreach ($this->all() as $error) {
            if ($error instanceof BaseError) {
                if ($error->haveMessage()) {
                    $messageGroupId = $error->getMessageGroup();
                    if ($messageGroupId) {
                        $errorsGrouped[$messageGroupId][] = $error;
                    } else {
                        $errorsNotGrouped[] = $error;
                    }
                }
            }
        }

        foreach ($errorsGrouped as $name => $errors) {
            $error = $errors[0];

            if (count($errors) === 1) {
                $messages[] = $error->getMessageFields();
            } else {
                $errors = new static($errors);
                $messagesTitles = $errors->map(function ($e) {
                    return $e->getMessage();
                });
                $messages[] = [
                    'type' => 'error',
                    'messages' => $messagesTitles
                ];
            }
        }

        foreach ($errorsNotGrouped as $error) {
            $messages[] = $error->getMessageFields();
        }

        /*

        // @var BaseError $error
        foreach ($this->all() as $error) {
            if ($error instanceof BaseError) {
                if ($error->haveMessage()) {
                    $nameInfo = $error->getNameInfo();
                    if ($nameInfo['messageGroupFields']) {
                        $errorsGrouped[$error->getName()][] = $error;
                    } else {
                        $errorsNotGrouped[] = $error;
                    }
                }
            }
        }

        foreach ($errorsGrouped as $name => $errors) {
            $error = $errors[0];
            $nameInfo = $error->getNameInfo();
            $errors = new static($errors);
            $message = $nameInfo['messageGroupFields']($errors) + $error->getMessageFields();
            $messages[] = $message;
        }
        */

        return $messages;
    }
}
