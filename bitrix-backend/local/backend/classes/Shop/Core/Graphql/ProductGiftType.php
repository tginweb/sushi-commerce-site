<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ProductGiftType extends ObjectType
{
    const NAME = 'ProductGift';

    public function getFieldsInfo()
    {
        return [
            'GIFT_ID' => Types::string(),
            'NAME' => Types::string(),
            'IMAGE' => Types::get(ImageType::class),
        ];
    }
}
