<?php

namespace Page\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class Page extends ElementModel
{
    const PROPS_ALL_PUBLIC = true;

    public function getUrl()
    {
        return rtrim($this['CODE'], '/') . '/';
    }

    public function getRouteData()
    {
        return [
            'PATH' => $this['CODE'],
            'NAME' => $this['NAME']
        ];
    }

    function getDataChunksFromProperty()
    {
        $res = [];

        foreach ($this['PROPERTY_DATA_CHUNKS_VALUE'] as $index => $item) {
            $item['CODE'] = $this['PROPERTY_DATA_CHUNKS_DESCRIPTION'][$index];
            $value = $this->getHooks()->apply_filters('main:compile_vars', $item['TEXT']);
            $valueData = json_decode($value);
            if ($valueData) {
                $item['VALUE'] = $valueData;
                $item['TYPE'] = 'DATA';
            } else {
                $item['VALUE'] = $value;
            }
            $res[] = $item;
        }
        return $res;
    }

    function getDataChunks()
    {
        return $this->getDataChunksFromProperty();
    }
}
