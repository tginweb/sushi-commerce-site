<?php

namespace Main\Dataloader;

use CFile;
use Main\Entity\Image\ImageModel;
use Main\Registry;
use Overblog\DataLoader\DataLoader as VendorDataLoader;

class ImageLoader extends BaseDataloader
{
    function __construct()
    {
        Registry::dataloader('image', $this);
    }

    function createLoader()
    {
        return new VendorDataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {
        $arImages = [];

        $dbRes = CFile::GetList(array(), array("@ID" => implode(",", array_filter($keys))));

        while ($file = $dbRes->GetNext()) {
            $file["SRC"] = CFile::GetFileSRC($file);

            if (!$file["SRC"])
                continue;

            $image = new ImageModel($file['ID'], $file);

            $arImages[$file['ID']] = $image;
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($arImages) {
            return $arImages[$id];
        }, $keys));
    }
}




