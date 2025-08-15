<?php

namespace Menu\Core\Graphql;

use Main\Graphql\Type\CommandType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class MenuItemType extends ObjectType
{
    const NAME = 'MenuItem';

    public function getFieldsInfo()
    {
        return [
            'id' => Types::string(),
            'parent' => Types::string(),
            'label' => Types::string(),
            'infoLabel' => Types::string(),
            'command' => Types::get(CommandType::class),
            'url' => Types::string(),
            'blank' => Types::boolean(),
            'native' => Types::boolean(),
            'icon' => Types::string(),
            'imageId' => Types::int(),
            'image' => Types::get(ImageType::class),
            'display' => Types::listOf(Types::string()),
            'color' => Types::string(),
            'bgColor' => Types::string(),
            'children' => Types::listOf(Types::get(MenuItemType::class)),
            'roles' => Types::listOf(Types::string()),
            'entityType' => Types::string(),
            'entityId' => Types::string(),

            'badge' => Types::JSON(),
            'params' => Types::JSON(),

            'outline' => Types::boolean(),
            'flat' => Types::boolean(),
            'width' => Types::string(),

            'onClick' => Types::JSON(),
            'dense' => Types::boolean(),
            'loading' => Types::boolean(),
            'disable' => Types::boolean(),

            'textColor' => Types::string(),
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
