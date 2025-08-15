<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Query;
use Main\Entity\Model\BaseBitrixModel;

class VorderQuery extends D7Query
{
    public $withData = false;

    protected function addItemToResults(&$results, BaseBitrixModel $object)
    {
        if ($this->withData) {
            $object->initVorder();
        }
        parent::addItemToResults($results, $object);
    }

    public function withData()
    {
        $this->withData = true;
        return $this;
    }
}
