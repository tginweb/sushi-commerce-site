<?php

namespace Main\Entity\Model;

use Illuminate\Support\Arr;
use LogicException;
use Main\Entity\Model\Traits\ModelEventsTrait;
use Main\Entity\Query\BaseQuery;

abstract class BaseBitrixModel extends ArrayableModel
{
    use ModelEventsTrait;

    const PROPS_ALL_PUBLIC = false;

    const ENTITY_TYPE = null;

    static $initedClasses = [];
    static $attrsInfo = [];

    /**
     * @var string|null
     */
    protected static $currentLanguage = null;
    public $queryClass;
    public $methodsLocalCache = [];
    /**
     * Array of model fields keys that needs to be saved with next save().
     *
     * @var array
     */
    protected $fieldsSelectedForSave = [];
    /**
     * Array of errors that are passed to model events.
     *
     * @var array
     */
    protected $eventErrors = [];
    /**
     * Have fields been already fetched from DB?
     *
     * @var bool
     */
    protected $fieldsAreFetched = false;

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
    }

    /**
     * Fill model fields if they are already known.
     * Saves DB queries.
     *
     * @param array $fields
     *
     * @return void
     */
    public function fill($fields)
    {
        $cls = get_called_class();

        $cls::init();

        if (!is_array($fields)) {
            return;
        }

        $fieldsRes = [];
        foreach ($fields as $fieldName => $fieldValue) {
            if (isset(static::FIELDS_MAP[$fieldName])) {
                $fieldsRes[static::FIELDS_MAP[$fieldName]] = $fieldValue;
            } else {
                $fieldsRes[$fieldName] = $fieldValue;
            }
        }

        if (isset($fieldsRes['ID'])) {
            $this->id = $fieldsRes['ID'];
        }

        $this->fields = $fieldsRes;

        $this->fieldsAreFetched = true;

        if (method_exists($this, 'afterFill')) {
            $this->afterFill();
        }

        $this->original = $this->fields;
    }

    static function init()
    {
        $cls = get_called_class();

        if (empty(self::$initedClasses[$cls])) {
            static::loadReferences();
            self::$initedClasses[$cls] = true;
        }
    }

    static function loadReferences()
    {

    }

    /**
     * Create new item in database.
     *
     * @param $fields
     *
     * @return static|bool
     * @throws LogicException
     *
     */
    public static function create($fields)
    {
        return static::internalCreate($fields);
    }

    /**
     * Internal part of create to avoid problems with static and inheritance
     *
     * @param $fields
     *
     * @return static|bool
     * @throws LogicException
     *
     */
    protected static function internalCreate($fields)
    {
        throw new LogicException('internalCreate is not implemented');
    }

    /**
     * Get count of items that match $filter.
     *
     * @param array $filter
     *
     * @return int
     */
    public static function count(array $filter = [])
    {
        return static::query()->filter($filter)->count();
    }

    /**
     * Get item by its id.
     *
     * @param int $id
     *
     * @return static|bool
     */
    public static function find($id)
    {
        return static::query()->getById($id);
    }

    /**
     * Handle dynamic static method calls into a new query.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return static::query()->$method(...$parameters);
    }

    /**
     * Get all model attributes from cache or database.
     *
     * @return array
     */
    public function get()
    {
        $this->load();

        return $this->fields;
    }

    /**
     * Load model fields from database if they are not loaded yet.
     *
     * @return $this
     */
    public function load()
    {
        if (!$this->fieldsAreFetched) {
            $this->refresh();
        }

        return $this;
    }

    /**
     * Refresh model from database and place data to $this->fields.
     *
     * @return array
     */
    public function refresh()
    {
        return $this->refreshFields();
    }

    /**
     * Refresh model fields and save them to a class field.
     *
     * @return array
     */
    public function refreshFields()
    {
        if ($this->id === null) {
            $this->original = [];
            return $this->fields = [];
        }

        $this->fields = static::query()->getById($this->id)->fields;
        $this->original = $this->fields;

        $this->fieldsAreFetched = true;

        return $this->fields;
    }

    /**
     * Instantiate a query object for the model.
     *
     * @return BaseQuery
     * @throws LogicException
     *
     */
    public static function query()
    {
        throw new LogicException('public static function query() is not implemented');
    }

    /**
     * Get model fields from cache or database.
     *
     * @return array
     */
    public function getFields()
    {
        if ($this->fieldsAreFetched) {
            return $this->fields;
        }

        return $this->refreshFields();
    }

    /**
     * Update model.
     *
     * @param array $fields
     *
     * @return bool
     */
    public function update(array $fields = [])
    {
        $keys = [];
        foreach ($fields as $key => $value) {
            Arr::set($this->fields, $key, $value);
            $keys[] = $key;
        }

        return $this->save($keys);
    }

    public function save($selectedFields = [])
    {

    }

    function getPropsClientData()
    {
        return [
            'SID' => intval($this['ID']),
        ];
    }

    public function getEntityType()
    {
        return static::ENTITY_TYPE;
    }

    /**
     * Set current model id.
     *
     * @param $id
     */
    protected function setId($id)
    {
        $this->id = $id;
        $this->fields['ID'] = $id;
    }

    /**
     * Create an array of fields that will be saved to database.
     *
     * @param $selectedFields
     *
     * @return array|null
     */
    protected function normalizeFieldsForSave($selectedFields)
    {
        $fields = [];
        if ($this->fields === null) {
            return [];
        }

        foreach ($this->fields as $field => $value) {
            if (!$this->fieldShouldNotBeSaved($field, $value, $selectedFields)) {
                $fields[$field] = $value;
            }
        }

        return $fields ?: null;
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

    }

    /**
     * Reset event errors back to default.
     */
    protected function resetEventErrors()
    {
        $this->eventErrors = [];
    }

    /**
     * Get value from language field according to current language.
     *
     * @param $field
     * @return mixed
     */
    protected function getValueFromLanguageField($field)
    {
        $key = $field . '_' . $this->getCurrentLanguage();
        return isset($this->fields[$key]) ? $this->fields[$key] : null;
    }

    /**
     * Getter for currentLanguage.
     *
     * @return string
     */
    public static function getCurrentLanguage()
    {
        return self::$currentLanguage;
    }

    /**
     * Setter for currentLanguage.
     *
     * @param $language
     * @return mixed
     */
    public static function setCurrentLanguage($language)
    {
        self::$currentLanguage = $language;
    }

    public static function getAttrsInfo()
    {
        return [];
    }

    public static function getAttrsFiltered($view = [])
    {
        $result = [];
        $view = (array)$view;
        $allViewMode = in_array('all', $view);
        $attrs = static::getAttrsInfo();

        foreach ($attrs as $name => $attr) {
            if (
                $allViewMode ||
                $attr['attrType'] === 'prop' && static::PROPS_ALL_PUBLIC ||
                isset($attr['view']) && (
                    $attr['view'] === true ||
                    !!count(array_intersect((array)$attr['view'], $view))
                )
            ) {
                $result[$name] = $attr;
            }
        }

        return $result;
    }

    public static function getAttrsFilteredByType($viewmode = [])
    {
        return array_reduce(static::getAttrsFiltered($viewmode), function ($result, $item) {
            $result[$item['attrType']][$item['code']] = $item;
            return $result;
        }, []);
    }

    public static function getAttrsProps($view = [])
    {
        return array_map(
            function ($attr) {
                return $attr['prop'];
            },
            array_filter(static::getAttrsFiltered($view), function ($item) {
                return $item['attrType'] == 'prop';
            })
        );
    }

    public static function getFilters($viewmode = [])
    {
        return [];
    }
}
