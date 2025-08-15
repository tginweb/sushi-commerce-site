<?php

namespace Main\Dataloader;

use CUserFieldEnum;
use Main\Registry;

class UfEnumLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('uf_enum', $this);
    }

    function batch($keys)
    {
        $obEnum = new CUserFieldEnum;
        $rsEnum = $obEnum->GetList(
            [],
            ["ID" => $keys]
        );

        $arEnums = [];
        while ($arEnum = $rsEnum->Fetch()) {
            $arEnums[$arEnum['ID']] = $arEnum;
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($arEnums) {
            return $arEnums[$id];
        }, $keys));
    }

}




