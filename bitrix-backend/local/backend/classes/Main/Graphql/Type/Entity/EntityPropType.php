<?php

namespace Main\Graphql\Type\Entity;

use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;

class EntityPropType extends EntityType
{
    const NAME = 'EntityProp';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'CODE' => Types::string(),
            'NAME' => Types::string(),
            'TYPE' => Types::string(),
            'VAL' => Types::JSON(),
            'VAL_ID' => Types::JSON(),
            'VAL_ENUM_ID' => Types::JSON(),
            'DESC' => Types::JSON(),
            'MUL' => Types::boolean(),
            'FILE' => Types::get(ImageType::class),
            'FILES' => Types::listOf(Types::get(ImageType::class)),
        ];
    }

    function resolve_FILE($element, $args, $context)
    {
        return ($element['TYPE'] === 'F' || $element['TYPE'] === 'file') && $element['VAL'] && !$element['MUL'] ? $context['dataloader']['image']->load($element['VAL']) : null;
    }

    function resolve_FILES($element, $args, $context)
    {
        return ($element['TYPE'] === 'F' || $element['TYPE'] === 'file') && $element['VAL'] && $element['MUL'] ? $context['dataloader']['image']->loadMany($element['VAL']) : null;
    }
}
