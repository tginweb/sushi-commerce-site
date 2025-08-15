<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class ElementPropValueWithDescType extends EntityType
{
    const NAME = 'ElementPropValueWithDesc';

    public function getFieldsInfo()
    {
        return [
            'VALUE' => Types::string(),
            'DESC' => Types::string(),
        ];
    }
}
