<?php

namespace Shop\Core\Dataloader;

use Main\Dataloader\BaseDataloader;
use Shop\Core\Entity\OrderModel;

class OrderLoader extends BaseDataloader
{
    function __construct()
    {
        $this->container->getRegistryService()->dataloader('order', $this);
    }

    function batch($keys)
    {
        list($keys, $field) = $this->extractKeys($keys);

        $ids = $keys;

        $orders = OrderModel::query()->filter([
            'ID' => array_filter($ids),
        ])->getList();

        $ordersById = [];

        foreach ($orders as $order) {
            $ordersById[$order->getId()] = $order;
        }

        $items = array_map(function ($id) use ($ordersById) {
            return $id ? $ordersById[$id] : null;
        }, $ids);

        if ($field) {
            $items = array_column($items, $field);
        }

        return $this->getPromiseAdapter()->createAll($items);
    }
}




