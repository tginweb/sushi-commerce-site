<?php

namespace Main\Traits\EventTemplate;

trait Shop
{
    function formatPrice($val)
    {
        return $val . ' &#8381;';
    }
}
