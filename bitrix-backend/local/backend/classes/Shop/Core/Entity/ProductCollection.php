<?php

namespace Shop\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\IBlock\ElementCollection;

class ProductCollection extends ElementCollection
{
    use Containerable;

    public function withParents()
    {
        $parentIds = [];

        foreach ($this->all() as $item) {
            if ($item['PROPERTY_CML2_LINK_VALUE']) {
                $parentIds[$item['PROPERTY_CML2_LINK_VALUE']] = $item['PROPERTY_CML2_LINK_VALUE'];
            }
        }

        if (!empty($parentIds)) {
            $query = $this->container->getCatalogProductElementModelClass()::query()->withViewPublic();
            $parentProducts = $query->filter(['ID' => $parentIds])->getList();

            foreach ($parentProducts as $parentProduct) {
                $this->offsetSet($parentProduct['ID'], $parentProduct);
            }
        }

        foreach ($this->all() as $item) {
            $parentId = $item['PROPERTY_CML2_LINK_VALUE'];
            if ($parentId) {
                $parentMode = $this[$parentId];
                if ($parentMode) {
                    $item->setData('PARENT', $parentMode);
                }
            }
        }

        return $this;
    }

}
