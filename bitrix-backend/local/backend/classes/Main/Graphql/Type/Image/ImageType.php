<?php

namespace Main\Graphql\Type\Image;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\Image\ThumbModel;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ImageType extends ObjectType
{
    const NAME = 'Image';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::id(),
            'SRC' => Types::string(),
            'SRC_DEFAULT' => Types::string(),
            'FILE_SIZE' => Types::string(),
            'ORIGINAL_NAME' => Types::string(),
        ];
    }

    public function resolve_SRC_DEFAULT($element, $args, $ctx)
    {
        $styler = $this->container->getImageService()->getStylerProvider();
        if ($styler) {
            return $styler->style($element['SRC'], 400);
        }
    }

    public function resolve_THUMBS($element, $args, $context, ResolveInfo $info)
    {
        $sizes = [];

        if ($args['size']) {
            $sizes[] = $args['size'];
        }

        if ($args['sizes']) {
            $sizes = array_merge($sizes, $args['sizes']);
        }

        $result = [];

        foreach ($sizes as $size) {
            $result[] = new ThumbModel(null, [
                'STYLE' => $size,
                'SRC' => $size . $element['SRC']
            ]);
        }

        return $result;
    }
}
