<?php

namespace Main\Entity\Model;

use Main\Error\Exception\ExceptionFromBitrix;
use Main\Service\IBlockService;

abstract class BitrixModel extends BaseBitrixModel
{

    public static $bxObject;

    public static $fetchUsing = [
        'method' => 'Fetch',
        'params' => [],
    ];

    protected static $objectClass = '';

    public function __construct($id = null, $fields = null)
    {
        static::instantiateObject();

        parent::__construct($id, $fields);
    }

    public static function instantiateObject()
    {
        if (static::$bxObject) {
            return static::$bxObject;
        }

        if (class_exists(static::$objectClass)) {
            return static::$bxObject = new static::$objectClass();
        }

        throw new \LogicException('Object initialization failed');
    }

    static function getIblockService()
    {
        return IBlockService::i();
    }

    protected static function internalCreate($fields)
    {
        $model = new static(null, $fields);

        if ($model->onBeforeSave() === false || $model->onBeforeCreate() === false) {
            return false;
        }

        $bxObject = static::instantiateObject();
        $id = static::internalDirectCreate($bxObject, $model->fields);

        $model->setId($id);

        $result = $id ? true : false;

        $model->setEventErrorsOnFail($result, $bxObject);
        $model->onAfterCreate($result);
        $model->onAfterSave($result);
        $model->resetEventErrors();
        $model->throwExceptionOnFail($result, $bxObject);

        return $model;
    }

    public static function internalDirectCreate($bxObject, $fields)
    {
        return $bxObject->add($fields);
    }

    public function save($selectedFields = [])
    {
        if (!$this['ID']) {
            return static::create($this->fields);
        }

        $fieldsSelectedForSave = is_array($selectedFields) ? $selectedFields : func_get_args();
        $this->fieldsSelectedForSave = $fieldsSelectedForSave;
        if ($this->onBeforeSave() === false || $this->onBeforeUpdate() === false) {
            $this->fieldsSelectedForSave = [];
            return false;
        } else {
            $this->fieldsSelectedForSave = [];
        }

        $fields = $this->normalizeFieldsForSave($fieldsSelectedForSave);

        $result = $fields === null
            ? true
            : $this->internalUpdate($fields, $fieldsSelectedForSave);

        $this->setEventErrorsOnFail($result, static::$bxObject);
        $this->onAfterUpdate($result);
        $this->onAfterSave($result);
        $this->resetEventErrors();
        $this->throwExceptionOnFail($result, static::$bxObject);

        return $result;
    }

    protected function internalUpdate($fields, $fieldsSelectedForSave)
    {
        return !empty($fields) ? static::$bxObject->update($this->id, $fields) : false;
    }

    protected function setEventErrorsOnFail($result, $bxObject)
    {
        if (!$result) {
            $this->eventErrors = (array)$bxObject->LAST_ERROR;
        }
    }

    protected function throwExceptionOnFail($result, $bxObject)
    {
        if (!$result) {
            throw new ExceptionFromBitrix($bxObject->LAST_ERROR);
        }
    }

    public function deactivate()
    {
        $this->fields['ACTIVE'] = 'N';

        return $this->save(['ACTIVE']);
    }

    public function delete()
    {
        if ($this->onBeforeDelete() === false) {
            return false;
        }

        $result = static::$bxObject->delete($this->id);

        $this->setEventErrorsOnFail($result, static::$bxObject);
        $this->onAfterDelete($result);
        $this->resetEventErrors();
        $this->throwExceptionOnFail($result, static::$bxObject);

        return $result;
    }

    protected function fieldShouldNotBeSaved($field, $value, $selectedFields)
    {
        $blacklistedFields = [
            'ID',
            'IBLOCK_ID',
            'GROUPS',
        ];
        return (!empty($selectedFields) && !in_array($field, $selectedFields))
            || in_array($field, $blacklistedFields)
            || ($field[0] === '~')
            || (substr($field, 0, 9) === 'PROPERTY_')
            || (is_array($this->original) && array_key_exists($field, $this->original) && $this->original[$field] === $value);
    }
}
