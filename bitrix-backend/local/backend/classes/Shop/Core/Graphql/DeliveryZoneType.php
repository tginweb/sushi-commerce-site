<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;
use Shop\Core\Entity\DeliveryZone;

class DeliveryZoneType extends ElementType
{
    const NAME = 'DeliveryZone';

    static function getModelClass()
    {
        return DeliveryZone::class;
    }

    public function getFieldsInfo()
    {
        return parent::getBaseFieldsInfo() + [
                'PREVIEW_TEXT' => [
                    'args' => [
                        'format' => Types::boolean(),
                    ],
                    'type' => Types::JSON(),
                ],
            ] + parent::getPropsFieldsInfo();
    }
}
