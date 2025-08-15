<?php

namespace Main\Dataloader;

use CIBlockPropertyEnum;
use Main\Registry;
use Overblog\DataLoader\DataLoader;

class EnumLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('enum', $this);
    }

    function createLoader()
    {
        return new DataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {

        list($keys) = $this->extractKeys($keys);

        $rsEnums = CIBlockPropertyEnum::GetList(
            [
                "DEF" => "DESC",
                "SORT" => "ASC"
            ],
            [
                'ID' => $keys,
            ]
        );

        $itemsById = [];

        while ($item = $rsEnums->Fetch()) {
            $itemsById[$item['ID']] = $item;
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($itemsById) {
            return $itemsById[$id];
        }, $keys));
    }
}




