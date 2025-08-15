<?php

namespace Shop\Core\Entity\OrderProperty;

use Main\Graphql\Types;
use Shop\Core\Entity\OrderProperty;

class Enum extends OrderProperty
{
    public $enumOptions;

    public function getEnumOptions($value = null, $order = null, $refetch = false)
    {
        if (!isset($this->enumOptions) || $refetch) {
            $this->enumOptions = [];
            switch ($this->getType()) {
                case 'ENUM':
                    $rsVariants = \Bitrix\Sale\Internals\OrderPropsVariantTable::getList([
                        'filter' => ['ORDER_PROPS_ID' => $this->getId()],
                        'order' => ['SORT' => 'ASC']
                    ]);
                    while ($row = $rsVariants->fetch()) {
                        $this->enumOptions[$row['VALUE'] ?? $row['ID']] = $row;
                    }
                    break;
            }
        }
        return $this->enumOptions;
    }

    public function getGraphqlType()
    {
        return Types::enumInstance('Attr'. $this->getCamelCode() . 'Enum', $this->getEnumOptions());
    }
}


