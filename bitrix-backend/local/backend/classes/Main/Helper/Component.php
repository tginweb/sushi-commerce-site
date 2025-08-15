<?php

namespace Main\Helper;

class Component
{
    public static function startsWith($haystack, $needle)
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
