<?php

namespace Main\Entity\IBlock;

use CFile;
use Main\Entity\Image\ImageCollection;
use Main\Entity\Image\ImageModel;
use Main\Entity\Model\ModelCollection;

class ElementCollection extends ModelCollection
{
    public function withImages()
    {
        $images = $this->getItemsImages();

        foreach ($this->all() as $item) {

            if (!empty($item['DETAIL_PICTURE'])) {
                $item->setData('DETAIL_PICTURE', $images[$item['DETAIL_PICTURE']]);
            }
            if (!empty($item['PREVIEW_PICTURE'])) {
                $item->setData('PREVIEW_PICTURE', $images[$item['PREVIEW_PICTURE']]);
            }
        }

        return $this;
    }

    public function getItemsImages()
    {
        $arImageIds = $this->getItemsImagesIds();
        $arImages = [];

        if (!empty($arImageIds)) {
            $rs = CFile::GetList(array(), array("@ID" => implode(",", array_filter($arImageIds))));
            while ($file = $rs->GetNext()) {
                $file["SRC"] = CFile::GetFileSRC($file);
                $image = new ImageModel($file['ID'], $file);
                $arImages[$file['ID']] = $image;
            }
        }

        return new ImageCollection($arImages);
    }

    public function getItemsImagesIds()
    {
        $arImageIds = [];

        foreach ($this->all() as $item) {
            if (!empty($item['DETAIL_PICTURE']))
                $arImageIds[$item['DETAIL_PICTURE']] = $item['DETAIL_PICTURE'];
            if (!empty($item['PREVIEW_PICTURE']))
                $arImageIds[$item['PREVIEW_PICTURE']] = $item['PREVIEW_PICTURE'];
        }

        return $arImageIds;
    }

    public function withSections($methods = [])
    {
        foreach ($this->all() as $item) {
            $item->setData('SECTIONS', $item->getSections());
        }

        return $this;
    }
}
