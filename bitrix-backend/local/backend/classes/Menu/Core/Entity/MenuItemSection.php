<?php

namespace Menu\Core\Entity;

use Main\Entity\IBlock\SectionModel;

class MenuItemSection extends SectionModel
{
    const PROPS_ALL_PUBLIC = true;

    function getMenuItemData()
    {
        $item = [];

        $item['id'] = 's' . $this['ID'];
        $item['code'] = $this['CODE'];

        if ($this['IBLOCK_SECTION_ID'])
            $item['parent'] = 's' . $this['IBLOCK_SECTION_ID'];

        $item['label'] = $this['NAME'];
        $item['url'] = $this['UF_URL'];
        $item['color'] = $this['UF_COLOR'];
        $item['icon'] = $this['UF_ICON'];

        $item['display'] = $this->getProp('DISPLAY', 'XML_ID', true);

        $item['sort'] = $this['SORT'];

        return $item;
    }
}
