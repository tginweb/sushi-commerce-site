<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class OrderProfileCollection extends ModelCollection
{
    function prepareForClient() {
        foreach ($this->all() as $profile) {
            $profile->prepareForClient();
        }
        return $this;
    }

    function withValues()
    {
        $ids = $this->getIds();

        $query = [
            'select' => [
                'ID',
                'USER_PROPS_ID',
                'ORDER_PROPS_ID',
                'CODE' => 'PROPERTY.CODE',
                'PROP_NAME' => 'PROPERTY.NAME',
                'VALUE'
            ],
            'filter' => [
                'USER_PROPS_ID' => $ids
            ],
            'order' => [
                'PROPERTY.SORT' => 'ASC'
            ]
        ];

        $rs = \Bitrix\Sale\Internals\UserPropsValueTable::getList($query);

        $valuesByProfile = [];

        while ($value = $rs->fetch()) {
            $value['NAME'] = $value['PROP_NAME'];
            $valuesByProfile[$value['USER_PROPS_ID']][$value['ORDER_PROPS_ID']] = $value;
        }

        foreach ($this->all() as $profile) {
            $profile->setData('VALUES', $valuesByProfile[$profile['ID']]);
        }
        return $this;
    }
}


