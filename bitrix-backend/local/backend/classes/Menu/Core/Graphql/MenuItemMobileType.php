<?php

namespace Menu\Core\Graphql;

use Main\Graphql\Type\Action\ActionMobileType;
use Main\Graphql\Type\ConditionType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class MenuItemMobileType extends ObjectType
{
    const NAME = 'MenuItemMobile';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::string(),
            'parent' => Types::string(),
            'label' => Types::string(),
            'icon' => Types::string(),
            'imageId' => Types::int(),
            'image' => Types::get(ImageType::class),
            'color' => Types::string(),
            'backgroundColor' => Types::string(),
            'outlineColor' => Types::string(),
            'outlineWidth' => Types::int(),
            'link' => Types::boolean(),
            'outline' => Types::boolean(),
            'labelColor' => Types::string(),
            'badge' => Types::JSON(),
            'params' => Types::JSON(),
            'action' => Types::get(ActionMobileType::class),
            'condition' => Types::get(ConditionType::class),
            'templatable' => Types::boolean(),
            'templatableProps' => Types::listOf(Types::string()),

            'roles' => Types::JSON(), // onResponse
        ];
    }

    function resolve_params($element, $args, $context)
    {
        return $element['params'] ?: ['_hold' => true];
    }

    function resolve_image($element, $args, $context)
    {
        if (!empty($element['image']))
            return $element['image'];

        $pictureId = $element['imageId'];

        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }
}
