<?php

namespace Main\Entity\User;

use Main\Entity\D7\D7Model;

class UserFamily extends D7Model
{
    public static function tableName()
    {
        return 'user_family';
    }
}
