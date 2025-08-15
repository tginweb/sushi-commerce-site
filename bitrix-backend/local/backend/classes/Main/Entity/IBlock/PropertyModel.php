<?php

namespace Main\Entity\IBlock;

use Main\Entity\D7\D7Model;

class PropertyModel extends D7Model
{
    function __construct($id = null, $fields = null)
    {
        $this->id = $id;
        $this->fill($fields);
    }

    /**
     * Instantiate a query object for the model.
     *
     * @return PropertyQuery
     */
    static function query()
    {
        return new PropertyQuery(PropertyModel::class, PropertyCollection::class);
    }
}
