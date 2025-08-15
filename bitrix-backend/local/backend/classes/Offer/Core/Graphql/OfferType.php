<?php

namespace Offer\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;
use Menu\Core\Graphql\MenuItemMobileType;
use Menu\Core\Graphql\MenuItemType;
use Offer\Core\Entity\OfferModel;

class OfferType extends ElementType
{
    const NAME = 'Offer';

    static function iblockId()
    {
        return OfferModel::getIblockIdOrThrow();
    }

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'VID' => Types::string(),
                'OFFER_NAME' => Types::string(),
                'STARTUP_SHOW' => Types::string(), // once | ever
                'BANNER_INTERNAL_TEXT' => Types::string(),
                'BANNER_HOR_MOBILE' => Types::get(ImageType::class),
                'BANNER_HOR_DESKTOP' => Types::get(ImageType::class),
                'BANNER_SQUARE' => Types::get(ImageType::class),
                'DISCOUNT_ID' => Types::int(),
                'CONTENT_IMAGE' => Types::get(ImageType::class),
                'SLIDES' => [
                    'type' => Types::listOf(Types::get(OfferSlideType::class)),
                ],
                'ACTIONS_WEB' => Types::listOf(Types::get(MenuItemType::class)),
                'ACTIONS_MOBILE' => Types::listOf(Types::get(MenuItemMobileType::class)),
                'IS_HOT' => Types::boolean(),
                'VARS' => Types::JSON(),
                'VIEW_MODE' => Types::string(),
            ];
    }

    public function resolve_OFFER_NAME(OfferModel $element, $args, $context)
    {
        return $element->getProp('OFFER_NAME') ?: $element['NAME'];
    }

    public function resolve_VID(OfferModel $element, $args, $context)
    {
        return $element->getVid();
    }

    public function resolve_STARTUP_SHOW(OfferModel $element, $args, $context)
    {
        return $element->getProp('STARTUP_SHOW', 'XML_ID');
    }

    public function resolve_VIEW_MODE($element, $args, $context)
    {
        return $element->getViewMode();
    }

    public function resolve_IS_HOT($element, $args, $context)
    {
        return $element->isHot();
    }

    public function resolve_ACTIONS_WEB($element, $args, $context)
    {
        return $element->getActions('web');
    }

    public function resolve_ACTIONS_MOBILE($element, $args, $context)
    {
        return $element->getActions('mobile');
    }

    public function resolve_VARS($element, $args, $context)
    {
        return $element->getContextVars();
    }

    public function resolve_DISCOUNT_ID($element, $args, $context)
    {
        return $element->getProp('DISCOUNT_ID');
    }

    public function resolve_BANNER_HOR_MOBILE($element, $args, $context)
    {
        $pictureId = $element->getProp('BANNER_HOR_MOBILE');
        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_BANNER_HOR_DESKTOP($element, $args, $context)
    {
        $pictureId = $element->getProp('BANNER_HOR_DESKTOP');
        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_BANNER_SQUARE($element, $args, $context)
    {
        $pictureId = $element->getProp('BANNER_SQUARE');
        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_BANNER_IMAGE($element, $args, $context)
    {
        $pictureId = $element->getProp('BANNER_IMAGE') ?: $element->getProp('CONTENT_IMAGE');
        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_SLIDES($element, $args, $context)
    {
        return $element->getSlides();
    }
}
