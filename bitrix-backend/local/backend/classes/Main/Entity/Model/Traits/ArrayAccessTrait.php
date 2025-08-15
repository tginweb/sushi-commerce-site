<?php

namespace Main\Entity\Model\Traits;

use ArrayIterator;
use ReturnTypeWillChange;

trait ArrayAccessTrait
{
    /**
     * Returns an iterator for traversing the data.
     * This method is required by the SPL interface [[\IteratorAggregate]].
     * It will be implicitly called when you use `foreach` to traverse the collection.
     * @return ArrayIterator an iterator for traversing the cookies in the collection.
     */
    #[ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }

    /**
     * Returns the number of data items.
     * This method is required by Countable interface.
     * @return int number of data elements.
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return count($this->fields);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param mixed $offset the offset to check on
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param int $offset the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->fields[$offset]) ? $this->fields[$offset] : null;
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param int $offset the offset to set element
     * @param mixed $item the element value
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $item)
    {
        $this->fields[$offset] = $item;
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param mixed $offset the offset to unset element
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }
}
