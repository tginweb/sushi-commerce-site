<?php

namespace Search\Core\Entity;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;

class SearchResult extends ArrayableModel
{
    use Containerable;

    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    function getHint()
    {
        if ($this['ENTITY_TYPE_CODE']) {
            switch ($this['ENTITY_TYPE_CODE']) {
                case 'products':
                    if ($this['ENTITY_ROLE'] === 'section') {
                        return 'Категория';
                    }
                    break;
                case 'catalog_tag':
                    if ($this['ENTITY_ROLE'] === 'element') {
                        return 'в составе';
                    }
                    break;
            }
        }
    }

    function getClientData()
    {
        return [
            'entityTypeName' => $this['ENTITY_TYPE_NAME'],
            'entityTypeCode' => $this['ENTITY_TYPE_CODE'],
            'entityTypeId' => $this['ENTITY_TYPE_ID'],
            'entityRole' => $this['ENTITY_ROLE'],
            'entityId' => $this['ENTITY_ID'],
            'entityTitle' => $this['ENTITY_TYPE_TITLE'],
            'hint' => $this->getHint(),
        ];
    }
}
