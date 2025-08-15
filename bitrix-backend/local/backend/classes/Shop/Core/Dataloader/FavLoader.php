<?php

namespace Shop\Core\Dataloader;

use Main\Dataloader\BaseDataloader;
use Shop\Core\Entity\FavModel;

class FavLoader extends BaseDataloader
{
    function __construct()
    {
        $this->container->getRegistryService()->dataloader('fav', $this);
    }

    function batch($keys)
    {
        $filter = [
            'UF_ELEMENT_ID' => $keys,
        ];

        $filter[] = $this->container->getFavoritesService()->getClientFilter();

        $favs = FavModel::query()->filter($filter)->getList();

        $result = [];

        foreach ($favs as $fav) {
            if (!$result[$fav['UF_ELEMENT_ID']] || $fav['UF_TYPE'] === 'fav') {
                $result[$fav['UF_ELEMENT_ID']] = $fav;
            }
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($result) {
            return $result[$id];
        }, $keys));
    }
}




