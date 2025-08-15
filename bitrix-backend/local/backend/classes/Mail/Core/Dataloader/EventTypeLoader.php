<?php

namespace Mail\Core\Dataloader;

use Main\Dataloader\BaseDataloader;
use Overblog\DataLoader\DataLoader as VendorDataLoader;

class EventTypeLoader extends BaseDataloader
{
    function __construct()
    {
        $this->container->getRegistryService()->dataloader('mailEventType', $this);
    }

    function createLoader()
    {
        return new VendorDataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {
        $rows = [];

        $dbRes = \Bitrix\Main\Mail\Internal\EventTypeTable::getList(array(
            'filter' => [
                'EVENT_NAME' => $keys,
                'LID' => 'ru'
            ],
            'select' => array(
                '*'
            ),
        ));

        while ($row = $dbRes->fetch()) {
            $rows[$row['EVENT_NAME']] = $row;
        }

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($rows) {
            return $rows[$id];
        }, $keys));
    }

}




