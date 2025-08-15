<?php

namespace Main\Entity\D7;

class HLModel extends D7Model
{
    public static function query()
    {
        return new HLQuery(static::instantiateAdapter(), get_called_class());
    }
}
