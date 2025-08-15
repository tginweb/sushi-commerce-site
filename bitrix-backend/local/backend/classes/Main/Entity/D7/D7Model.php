<?php

namespace Main\Entity\D7;

use Bitrix\Main\Entity\Result;
use Bitrix\Main\Entity\UpdateResult;
use Main\DI\Containerable;
use Main\Entity\Model\BaseBitrixModel;
use Main\Entity\Model\ModelCollection;
use Main\Error\Exception\ExceptionFromBitrix;
use Main\Helper\HLblock;

class D7Model extends BaseBitrixModel
{
    use Containerable;

    /**
     * @var null|string
     */
    protected static $cachedTableClasses = [];
    /**
     * Array of adapters for each model to interact with Bitrix D7 API.
     *
     * @var D7Adapter[]
     */
    protected static $adapters = [];
    public $jsonFields = [];

    /**
     * Constructor.
     *
     * @param $id
     * @param $fields
     */
    public function __construct($id = null, $fields = null)
    {
        $this->id = $id;
        $this->fill($fields);
        static::instantiateAdapter();
    }

    /**
     * Instantiate adapter if it's not instantiated.
     *
     * @return D7Adapter
     */
    public static function instantiateAdapter()
    {
        $class = get_called_class();
        if (isset(static::$adapters[$class])) {
            return static::$adapters[$class];
        }

        return static::$adapters[$class] = new D7Adapter(static::cachedTableClass());
    }

    public static function getFieldsInfo()
    {
        return [
            'ID' => ['view' => true, 'cfilter' => true, 'type' => 'number'],
        ];
    }

    public static function getPropsInfo()
    {
        return [

        ];
    }

    public static function getAttrsInfo()
    {
        $cls = static::class;

        if (!isset(static::$attrsInfo[$cls])) {

            $result = [];

            $fields = static::getFieldsInfo();

            foreach ($fields as $code => $field) {
                $citem = [
                        'attrType' => 'field',
                        'code' => $code,
                        'fullName' => $code
                    ] + $field;
                $result[$code] = $citem;
            }

            $propsInfo = static::getPropsInfo();
            $props = static::container()->getFieldsService()->getUserFields(static::tableEntityId());

            foreach ($props as $code => $prop) {

                $info = $propsInfo[$code] ?? [];

                switch ($prop['USER_TYPE_ID']) {
                    case 'integer':
                    case 'double':
                        $info['type'] = 'number';
                        break;
                    case 'boolean':
                        $info['type'] = 'boolean';
                        break;
                    case 'datetime':
                        $info['type'] = 'datetime';
                        break;
                    case 'date':
                        $info['type'] = 'date';
                        break;
                    case 'string':
                        $info['type'] = 'string';
                        break;
                    case 'tg_enum':
                        $info['type'] = 'enum';
                        break;
                    default:
                        $info['type'] = 'string';
                }

                $item = [
                        'attrType' => 'prop',
                        'code' => $code,
                        'fullName' => 'UF_' . $code,
                        'prop' => $prop
                    ] + $info;
                $result[$code] = $item;
            }

            static::$attrsInfo[$cls] = $result;
        }

        return static::$attrsInfo[$cls];
    }


    public static function tableName()
    {
        return null;
    }

    public static function tableId()
    {
        $tableName = static::tableName();
        if ($tableName) {
            $info = HLblock::getByTableName($tableName);
            return $info ? $info['ID'] : null;
        }
    }

    public static function tableClass()
    {
        $cls = HLblock::compileClass(static::tableName());
        return $cls;
    }

    public static function tableEntityId()
    {
        return 'HLBLOCK_' . static::tableId();
    }

    public static function cachedTableClass()
    {
        $class = get_called_class();
        if (!isset(static::$cachedTableClasses[$class])) {
            static::$cachedTableClasses[$class] = static::tableClass();
        }
        return static::$cachedTableClasses[$class];
    }

    /**
     * Setter for adapter (for testing)
     * @param $adapter
     */
    public static function setAdapter($adapter)
    {
        static::$adapters[get_called_class()] = $adapter;
    }

    /**
     * Instantiate a query object for the model.
     *
     * @return D7Query
     */
    public static function query()
    {
        return new D7Query(static::instantiateAdapter(), get_called_class(), ModelCollection::class);
    }

    /**
     * Delete model
     *
     * @return bool
     * @throws ExceptionFromBitrix
     */
    public function delete()
    {
        if ($this->onBeforeDelete() === false) {
            return false;
        }

        $resultObject = static::instantiateAdapter()->delete($this->id);
        $result = $resultObject->isSuccess();

        $this->setEventErrorsOnFail($resultObject);
        $this->onAfterDelete($result);
        $this->resetEventErrors();
        $this->throwExceptionOnFail($resultObject);

        return $result;
    }

    /**
     * Set eventErrors field on error.
     *
     * @param Result $resultObject
     */
    protected function setEventErrorsOnFail($resultObject)
    {
        if (!$resultObject->isSuccess()) {
            $this->eventErrors = (array)$resultObject->getErrorMessages();
        }
    }

    /**
     * Throw bitrix exception on fail
     *
     * @param Result $resultObject
     * @throws ExceptionFromBitrix
     */
    protected function throwExceptionOnFail($resultObject)
    {
        if (!$resultObject->isSuccess()) {
            throw new ExceptionFromBitrix(implode('; ', $resultObject->getErrorMessages()));
        }
    }

    public function normalizeFieldsForCreate()
    {
        return $this->fields;
    }

    /**
     * Save model to database.
     *
     * @param array $selectedFields save only these fields instead of all.
     * @return bool
     * @throws ExceptionFromBitrix
     */
    public function save($selectedFields = [])
    {
        $fieldsSelectedForSave = is_array($selectedFields) ? $selectedFields : func_get_args();

        if (!$this->id) {
            $this->onBeforeSave();
            $entity = static::internalCreate($this);
            if ($entity) {
                $this->setId($entity->id);
            }
            return $entity ? true : false;
        }

        $this->fieldsSelectedForSave = $fieldsSelectedForSave;

        if ($this->onBeforeSave() === false || $this->onBeforeUpdate() === false) {
            $this->fieldsSelectedForSave = [];
            return false;
        } else {
            $this->fieldsSelectedForSave = [];
        }

        $fields = $this->normalizeFieldsForSave($fieldsSelectedForSave);
        $resultObject = $fields === null
            ? new UpdateResult()
            : static::instantiateAdapter()->update($this->id, $fields);


        $result = $resultObject->isSuccess();

        $this->setEventErrorsOnFail($resultObject);
        $this->onAfterUpdate($result);
        $this->onAfterSave($result);
        $this->throwExceptionOnFail($resultObject);

        return $result;
    }

    function onBeforeSave()
    {
        if (!empty($this->jsonFields)) {
            foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
                if (is_array($this[$jsonField]) || empty($this[$jsonField])) {
                    if (!empty($jsonFieldInfo['isArray'])) {
                        $this[$jsonField] = json_encode(array_values($this[$jsonField] ?? []), JSON_UNESCAPED_UNICODE);
                    } else {
                        $this[$jsonField] = json_encode($this[$jsonField] ?? ['hold' => true], JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }
    }

    /**
     * Internal part of create to avoid problems with static and inheritance
     *
     * @param $fields
     *
     * @return static|bool
     * @throws ExceptionFromBitrix
     *
     */
    protected static function internalCreate($fields)
    {
        if (!is_object($fields)) {
            $model = new static(null, $fields);
        } else {
            $model = $fields;
        }

        if ($model->onBeforeSave() === false || $model->onBeforeCreate() === false) {
            return false;
        }

        if (!is_object($fields)) {
            $fields = $model->fields;
        } else {
            $fields = $model->normalizeFieldsForSave([]);
        }

        $resultObject = static::instantiateAdapter()->add($fields);
        $result = $resultObject->isSuccess();

        if ($result) {
            $model->setId($resultObject->getId());
        }

        $model->setEventErrorsOnFail($resultObject);
        $model->onAfterCreate($result);
        $model->onAfterSave($result);
        $model->throwExceptionOnFail($resultObject);

        return $model;
    }

    function onAfterSave()
    {
        if (!empty($this->jsonFields)) {
            foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
                $this[$jsonField] = json_decode($this[$jsonField], true) ?? [];
            }
        }
    }

    function afterFill()
    {
        if (!$this['ID']) return;
        if (!empty($this->jsonFields)) {
            foreach ($this->jsonFields as $jsonField => $jsonFieldInfo) {
                $this[$jsonField] = json_decode($this[$jsonField], true) ?? [];
            }
        }
    }

    /**
     * Determine whether the field should be stopped from passing to "update".
     *
     * @param string $field
     * @param mixed $value
     * @param array $selectedFields
     *
     * @return bool
     */
    protected function fieldShouldNotBeSaved($field, $value, $selectedFields)
    {
        return (!empty($selectedFields) && !in_array($field, $selectedFields)) || $field === 'ID';
    }

    function getProp($name)
    {
        return $this[$name];
    }
}
