<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Model;

class Department extends D7Model
{
    const PROPS_ALL_PUBLIC = true;

    public static function tableName()
    {
        return 'sale_department';
    }
}
