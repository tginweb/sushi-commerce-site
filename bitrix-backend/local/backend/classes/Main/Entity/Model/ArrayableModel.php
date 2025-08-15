<?php

namespace Main\Entity\Model;

use ArrayAccess;
use ArrayIterator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use IteratorAggregate;
use Main\Entity\Model\Traits\HidesAttributes;
use TG\Main\Helper;

abstract class ArrayableModel implements ArrayAccess, Arrayable, Jsonable, IteratorAggregate
{
    use HidesAttributes;

    const FIELDS_MAP = [];

    public $data = [];

    public $collectionRef;
    public $queryRef;

    /**
     * ID of the model.
     *
     * @var null|int
     */
    public $id;

    /**
     * Array of model fields.
     *
     * @var null|array
     */
    public $fields;
    /**
     * Array related models indexed by the relation names.
     *
     * @var array
     */
    public $related = [];
    /**
     * @var array
     */
    public $isChanged = false;
    /**
     * Array of original model fields.
     *
     * @var null|array
     */
    protected $original;
    /**
     * Array of accessors to append during array transformation.
     *
     * @var array
     */
    protected $appends = [];
    /**
     * Array of language fields with auto accessors.
     *
     * @var array
     */
    protected $languageAccessors = [];

    /**
     * @return boolean
     */
    public function isChanged()
    {
        return $this->isChanged;
    }

    /**
     * Set method for ArrayIterator.
     *
     * @param $offset
     * @param $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (static::FIELDS_MAP[$offset]) {
            $offset = static::FIELDS_MAP[$offset];
        }

        $this->isChanged = true;
        if (is_null($offset)) {
            $this->fields[] = $value;
        } else {
            $this->fields[$offset] = $value;
        }
    }

    /**
     * Exists method for ArrayIterator.
     *
     * @param $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        if (static::FIELDS_MAP[$offset]) {
            $offset = static::FIELDS_MAP[$offset];
        }

        return $this->getAccessor($offset) || $this->getAccessorForLanguageField($offset)
            ? true : isset($this->fields[$offset]);
    }

    /**
     * Get accessor method name if it exists.
     *
     * @param string $field
     *
     * @return string|false
     */
    private function getAccessor($field)
    {
        $method = 'get' . Str::camel($field) . 'Attribute';

        return method_exists($this, $method) ? $method : false;
    }

    /**
     * Get accessor for language field method name if it exists.
     *
     * @param string $field
     *
     * @return string|false
     */
    private function getAccessorForLanguageField($field)
    {
        $method = 'getValueFromLanguageField';

        return in_array($field, $this->languageAccessors) && method_exists($this, $method) ? $method : false;
    }

    /**
     * Unset method for ArrayIterator.
     *
     * @param $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (static::FIELDS_MAP[$offset]) {
            $offset = static::FIELDS_MAP[$offset];
        }
        $this->isChanged = true;
        unset($this->fields[$offset]);
    }

    /**
     * Get method for ArrayIterator.
     *
     * @param $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (static::FIELDS_MAP[$offset]) {
            $offset = static::FIELDS_MAP[$offset];
        }

        $fieldValue = isset($this->fields[$offset]) ? $this->fields[$offset] : null;
        $accessor = $this->getAccessor($offset);
        if ($accessor) {
            return $this->$accessor($fieldValue);
        }

        $accessorForLanguageField = $this->getAccessorForLanguageField($offset);
        if ($accessorForLanguageField) {
            return $this->$accessorForLanguageField($offset);
        }

        return $fieldValue;
    }

    /**
     * Get an iterator for fields.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }

    /**
     * Add value to append.
     *
     * @param array|string $attributes
     * @return $this
     */
    public function append($attributes)
    {
        $this->appends = array_unique(
            array_merge($this->appends, is_string($attributes) ? func_get_args() : $attributes)
        );

        return $this;
    }

    /**
     * Setter for appends.
     *
     * @param array $appends
     * @return $this
     */
    public function setAppends(array $appends)
    {
        $this->appends = $appends;

        return $this;
    }

    /**
     * Convert model to json.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
    {

        $fieldsMapFliped = array_flip(static::FIELDS_MAP);

        $array = [];

        foreach ($this->fields as $key => $value) {
            if (isset($fieldsMapFliped[$key])) {
                $array[$fieldsMapFliped[$key]] = $value;
            } else {
                $array[$key] = $value;
            }
        }

        foreach ($this->appends as $accessor) {
            if (isset($this[$accessor])) {
                $array[$accessor] = $this[$accessor];
            }
        }

        foreach ($this->related as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $array[$key] = $value->toArray();
            } elseif (is_null($value) || $value === false) {
                $array[$key] = $value;
            }
        }

        if (count($this->getVisible()) > 0) {
            $array = array_intersect_key($array, array_flip($this->getVisible()));
        }

        if (count($this->getHidden()) > 0) {
            $array = array_diff_key($array, array_flip($this->getHidden()));
        }

        return $array;
    }

    function getData($name, $def = null)
    {
        return $this->data[$name] ?? (is_callable($def) ? $def() : $def);
    }

    function setData($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    function unsetData($name)
    {
        unset($this->data[$name]);
    }

    function getComputedData($name, $cb = null, $recalc = false)
    {
        if ($recalc || !isset($this->data[$name])) {
            $this->data[$name] = $cb();
        }
        return $this->data[$name];
    }

    function evalData($name, $cb)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            $this->data[$name] = $cb();
            return $this->data[$name];
        }
    }

    function getCollectionRef()
    {
        return $this->collectionRef;
    }

    function setCollectionRef($collection)
    {
        $this->collectionRef = $collection;
        return $this;
    }

    function getQueryRef()
    {
        return $this->queryRef;
    }

    function setQueryRef($query)
    {
        $this->queryRef = $query;
        return $this;
    }

    function fieldDeepGet($path, $def = null)
    {
        $res = \Main\Helper\Common::arrayGetNestedValue($this->fields, $path, $exists);
        return $exists ? $res : $def;
    }

    function fieldDeepSet($path, $value)
    {
        \Main\Helper\Common::arraySetNestedValue($this->fields, $path, $value);
        return $this;
    }

    public function __isset($key)
    {
        if (static::FIELDS_MAP[$key]) {
            $key = static::FIELDS_MAP[$key];
        }
        return isset($this->fields[$key]);
    }

    public function __get($key)
    {
        if (static::FIELDS_MAP[$key]) {
            $key = static::FIELDS_MAP[$key];
        }
        return $this->fields[$key];
    }

    public function __set($key, $value)
    {
        if (static::FIELDS_MAP[$key]) {
            $key = static::FIELDS_MAP[$key];
        }
        $this->fields[$key] = $value;
    }

}
