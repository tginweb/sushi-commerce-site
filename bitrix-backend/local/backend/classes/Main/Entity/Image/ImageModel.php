<?php

namespace Main\Entity\Image;

use Main\Entity\Model\ArrayableModel;

class ImageModel extends ArrayableModel
{

    public function __construct($id = null, $fields = null)
    {
        $this->id = $id;
        $this->fields = $fields;
    }
}
