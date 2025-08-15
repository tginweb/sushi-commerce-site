<?php

namespace Shop\Core\Entity;

use Main\Entity\IBlock\ElementQuery;
use Main\Entity\Model\ModelCollection;
use TG\Catalog\Core\Helper as CatalogHelper;

class ProductQuery extends ElementQuery
{
    public $select = ['FIELDS'];

    public $priceIds = [];
    public $offersQuery;

    public function withView($viewmode = [])
    {
        parent::withView($viewmode);
        return $this->withPriceByUser();
    }

    public function getModelClass($arItem = null)
    {
        return $this->modelName;
    }

    public function withPriceByUser()
    {
        return $this->withPrice(\Shop\Core\Helper\Price::getPriceTypes([], true));
    }

    public function withPrice($ids = [])
    {
        foreach ((array)$ids as $id) {
            if (is_array($id))
                $id = $id['ID'];
            $this->priceIds[] = $id;
            $this->select[] = 'CATALOG_GROUP_' . $id;
        }
        return $this;
    }

    /**
     * Подготавливает запрос и вызывает loadModels()
     *
     * @return ModelCollection
     */
    public function getList($collectionMethod = null, $refetch = false)
    {
        $models = parent::getList($collectionMethod, $refetch);
        if ($this->offersQuery) {
            $productsIds = $models->getIds();
            $this->offersQuery->filter(['PROPERTY_CML2_LINK' => $productsIds]);
            $this->offersQuery->select('PROPERTY_CML2_LINK');
            $offers = $this->offersQuery->getList();

            $offersByParentId = array_reduce($offers->all(), function ($map, $item) {
                $parentId = $item['PROPERTY_CML2_LINK_VALUE'] ?? null;
                if ($parentId) {
                    $map[$parentId][] = $item;
                }
                return $map;
            }, []);

            foreach ($models as &$model) {
                if (!empty($offersByParentId[$model['ID']]))
                    $model['OFFERS'] = $offersByParentId[$model['ID']];
            }
        }
        return $models;
    }

    public function withOffers($query)
    {
        $this->offersQuery = $query;
        return $this;
    }

}
