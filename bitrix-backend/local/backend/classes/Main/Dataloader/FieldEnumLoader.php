<?php

namespace Main\Dataloader;

use CUserFieldEnum;
use Main\Registry;
use Overblog\DataLoader\DataLoader;

class FieldEnumLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('field_enum', $this);
    }

    function createLoader()
    {
        return new DataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {
        list($keys) = $this->extractKeys($keys);

        $obEnum = new CUserFieldEnum;
        $rsEnum = $obEnum->GetList(
            [],
            [
                'ID' => $keys
            ]
        );

        $itemsById = [];

        while ($item = $rsEnum->Fetch()) {
            $itemsById[$item['ID']] = $item;
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($itemsById) {
            return $itemsById[$id];
        }, $keys));
    }
}




