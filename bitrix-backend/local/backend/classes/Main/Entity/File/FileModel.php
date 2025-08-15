<?php

namespace Main\Entity\File;

use Main\Entity\Model\ArrayableModel;

class FileModel extends ArrayableModel
{
    public function __construct($id = null, $fields = null)
    {
        $this->id = $id;

        $this->fields = $fields;
    }
}
