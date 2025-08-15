<?php

namespace Offer\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;

class OfferSlideType extends ElementType
{
    const NAME = 'OfferSlide';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'CODE' => Types::string(),
            'CONTENT_TYPE' => Types::string(),
            'CONTENT_IMAGE' => Types::get(ImageType::class),
            'CONTENT_HTML' => Types::string(),
            'BG_COLOR' => Types::string(),
        ];
    }

    public function resolve_CONTENT_HTML($element, $args, $context)
    {
        return $element['CONTENT_HTML']['TEXT'];
    }

    public function resolve_CONTENT_IMAGE($element, $args, $context)
    {
        $pictureId = $element['CONTENT_IMAGE'];
        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }
}
