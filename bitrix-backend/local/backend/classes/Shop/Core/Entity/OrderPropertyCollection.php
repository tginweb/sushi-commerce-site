<?php

namespace Shop\Core\Entity;

use Main\Entity\Model\ModelCollection;

class OrderPropertyCollection extends ModelCollection
{
    function filterByPersonTypeId($personTypeId)
    {
        return $this->filter(function ($item) use ($personTypeId) {
            return $item->getField('PERSON_TYPE_ID') == $personTypeId;
        });
    }

    function filterIsProfile()
    {
        return $this->filter(function ($item) {
            return $item->isProfile();
        });
    }
}


