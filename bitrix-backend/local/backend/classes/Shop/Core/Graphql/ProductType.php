<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;
use Shop\Core\Entity\Product;

class ProductType extends ElementType
{
    const NAME = 'Product';

    static function getModelClass()
    {
        return Product::class;
    }

    function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'PRICE' => Types::nonNull(Types::get(ProductPriceType::class)),
                'MEASURE' => Types::get(ProductMeasureType::class),
                'FLAGS' => Types::nonNullListOf(Types::getNonNull(ProductFlagType::class)),

                'TAGS' => Types::listOf(Types::get(ProductTagType::class)),

                'PARENT' => Types::getType('ProductElement'),
                'OFFERS' => Types::listOf(Types::getType('ProductElement')),

                'IS_SALE_SPECIAL' => Types::boolean(),
                'SALES_COUNT' => Types::int(),

                'WEIGHT' => Types::int(),

                'BUILD' => Types::get(ConstructorBuildType::class),

                'OFFER_PARENT_ELEMENT' => Types::getType('ProductElement'),

                'BENEFITS' => Types::nonNullListOf(Types::getNonNull(ProductBenefitType::class)),
                'SET_ITEMS' => Types::nonNullListOf(Types::getNonNull(ProducSetItemType::class)),
            ];
    }

    function resolve_SET_ITEMS(Product $element, $args, $ctx)
    {
        return $element->getSetItems();
    }

    function resolve_BENEFITS(Product $element, $args, $ctx)
    {
        return $element->getBenefits();
    }

    function resolve_SALES_COUNT(Product $element, $args, $ctx)
    {
        return $element->getSalesCount();
    }

    function resolve_IS_SALE_SPECIAL(Product $element, $args, $ctx)
    {
        return $element->isSaleSpecial();
    }

    function resolve_BUILD(Product $element, $args, $ctx)
    {
        if ($element->isBuild()) {
            return $element;
        }
    }

    function resolve_FLAG(Product $element, $args, $ctx)
    {
        $ids = $element->getProp('FLAG_ITEMS', 'VALUE', true);
        return !empty($ids) ? $ctx['dataloader']['element']->loadMany($ids) : [];
    }

    function resolve_WEIGHT(Product $element, $args, $ctx)
    {
        return $element->getWeight();
    }

    function resolve_PARENT(Product $element, $args, $context)
    {
        $parentId = $element['PROPERTY_CML2_LINK_VALUE'];
        //  die(json_encode($parentId));
        return $parentId ? $context['dataloader']['element']->load($parentId . '.' . $args['viewmode']) : null;
    }

    function resolve_OFFERS(Product $element, $args, $ctx)
    {
        return $element['OFFERS'];
    }

    function resolve_FLAGS(Product $element, $args, $ctx)
    {
        return $element->getFlags();
    }

    function resolve_PRICE(Product $element, $args, $ctx)
    {
        return $element->getCatalogFinalPrice() ?: ['PRICE' => 0];
    }

    function resolve_MEASURE(Product $element, $args, $ctx)
    {
        return $element->getCatalogMeasure();
    }

    function resolve_OFFER_PARENT_ELEMENT(Product $element, $args, $context)
    {
        $parentId = $element['PROPERTY_CML2_LINK_VALUE'];
        return $parentId ? $context['dataloader']['element']->load($parentId . '.' . $args['viewmode']) : null;
    }
}
