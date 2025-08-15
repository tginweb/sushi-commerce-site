<?php

namespace Shop\Core\Lib\SalePovider;

class Product extends Base
{
    function __construct()
    {
        $this->mixins([
            //SaleMixin\WithGift::class,
            //SaleMixin\Gift::class,
            //SaleMixin\Constructor::class,
        ]);
    }
}
