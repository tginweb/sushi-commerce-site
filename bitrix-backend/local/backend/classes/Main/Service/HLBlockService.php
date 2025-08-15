<?php

namespace Main\Service;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;
use Main\Helper\HLblock;

class HLBlockService extends BaseService
{
    function __construct()
    {
        Loader::includeModule("highloadblock");
    }

    function getEntityInfoId($id)
    {
        $hlblock = $this->getEntityInfo($id);
        if ($hlblock) {
            return $hlblock['ID'];
        }
    }

    function getEntityInfo($id)
    {
        if (is_numeric($id)) {
            return HL\HighloadBlockTable::getById($id)->fetch();
        } else {
            return HLblock::getByTableName($id);
        }
    }

    function getEntityTableClass($id)
    {
        $hlblock = $this->getEntityInfo($id);
        if ($hlblock) {
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            return $entity_data_class;
        }
    }

    function getEntityFieldsName($id)
    {
        if (preg_match('/HLBLOCK/', $id)) {
            return $id;
        } else {
            $hlblock = $this->getEntityInfo($id);
            if ($hlblock) {
                return 'HLBLOCK_' . $hlblock['ID'];
            }
        }
    }
}


