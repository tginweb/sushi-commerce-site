<?php

namespace Main\Graphql\Type\IBlock;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;

class ElementPropType extends EntityType
{
    const NAME = 'ElementProp';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::nonNull(Types::int()),
            'CODE' => Types::nonNull(Types::string()),
            'NAME' => Types::nonNull(Types::string()),
            'TYPE' => Types::string(),
            'VAL' => Types::JSON(),
            'VAL_ID' => Types::JSON(),
            'VAL_ENUM_ID' => Types::JSON(),
            'DESC' => Types::JSON(),
            'MUL' => Types::boolean(),
            'FILE' => Types::get(ImageType::class),
            'FILES' => Types::listOf(Types::get(ImageType::class)),
            'FEATURES' => Types::JSON(),
            'OPTIONS' => Types::listOf(Types::get(ElementPropEnumType::class)),
        ];
    }

    function resolve_OPTIONS($element, $args, $context)
    {
        return $this->container->getIblockService()->getEnumsListCached($element['IBLOCK_ID'], 'STATUS');
    }

    function resolve_FILE($element, $args, $context)
    {
        return ($element['TYPE'] === 'F') && $element['VAL'] && !$element['MUL'] ? $context['dataloader']['image']->load($element['VAL']) : null;
    }

    function resolve_FILES($element, $args, $context)
    {
        return ($element['TYPE'] === 'F') && $element['VAL'] && $element['MUL'] ? $context['dataloader']['image']->loadMany($element['VAL']) : null;
    }
}
