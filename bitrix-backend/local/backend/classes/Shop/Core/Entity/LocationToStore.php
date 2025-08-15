<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ArrayableModel;

class LocationToStore extends ArrayableModel
{
    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }
}
