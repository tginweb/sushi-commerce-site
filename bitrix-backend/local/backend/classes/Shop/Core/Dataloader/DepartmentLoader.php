<?php

namespace Shop\Core\Dataloader;

use Main\Dataloader\BaseDataloader;
use Shop\Core\Entity\Department;

class DepartmentLoader extends BaseDataloader
{
    function __construct()
    {
        $this->container->getRegistryService()->dataloader('sale_department', $this);
    }

    function batch($keys)
    {
        list($keys, $field) = $this->extractKeys($keys);

        $ids = $keys;

        $items = Department::query()->filter([
            'ID' => array_filter($ids),
        ])->getList();

        $itemsById = [];

        foreach ($items as $item) {
            $itemsById[$item['ID']] = $item;
        }

        $items = array_map(function ($id) use ($itemsById) {
            return $id ? $itemsById[$id] : null;
        }, $ids);

        if ($field) {
            $items = array_column($items, $field);
        }

        return $this->getPromiseAdapter()->createAll($items);
    }
}




