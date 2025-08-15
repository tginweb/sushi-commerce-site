<?php

namespace Menu\Core\Entity;

class MenuItemMobile extends MenuItem
{
    const PROPS_ALL_PUBLIC = true;

    function getMenuItemData()
    {
        $item = [];

        $item['id'] = 'e' . $this['ID'];
        $item['code'] = $this['CODE'];

        if ($this['IBLOCK_SECTION_ID'])
            $item['parent'] = 's' . $this['IBLOCK_SECTION_ID'];

        $item['label'] = $this['NAME'];
        $item['url'] = $this->getProp('URL');
        $item['blank'] = $this->getProp('BLANK') === 'Y';
        $item['native'] = $this->getProp('NATIVE') === 'Y';
        $item['color'] = $this->getProp('COLOR');
        $item['icon'] = $this->getProp('ICON');
        $item['display'] = $this->getProp('DISPLAY', 'XML_ID', true);
        $item['entityType'] = $this->getProp('ENTITY_TYPE');
        $item['sort'] = $this['SORT'];
        $item['action'] = [
            'url' => $this->getProp('URL')
        ];

        return $item;
    }
}
