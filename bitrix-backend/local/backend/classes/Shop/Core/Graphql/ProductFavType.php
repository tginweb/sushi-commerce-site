<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;

class ProductFavType extends HLEntityType
{
    const NAME = 'CatalogFav';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'TYPE' => Types::string(),
                'TYPE_NAME' => Types::string(),
                'USER_ID' => Types::int(),
                'IBLOCK_ID' => Types::int(),
                'ELEMENT_ID' => Types::int(),

                'BASKET_HASH' => Types::string(),
                'SALES_COUNT' => Types::int(),

                'BASKET_ITEM' => Types::get(BasketItemType::class),
            ];
    }

    public function resolve_BASKET_ITEM($parent, $args, $ctx)
    {
        return;
    }

    public function resolve_TYPE_NAME($parent, $args, $ctx)
    {
        return $this->container->getFavoritesService()->getFavType($parent['UF_TYPE'], 'name');
    }

    public function resolve_ELEMENT($parent, $args, $ctx)
    {
        return $ctx['dataloader']['element']->load($parent['UF_ELEMENT_ID'] . '.list');
    }
}
