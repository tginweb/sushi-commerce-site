<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;
use Shop\Core\Entity\VorderCurrent;

class VOrderApplyActionTypeEnum extends BaseEnum
{
    public static function values()
    {
        return VorderCurrent::getApplyActionNames();
    }
}
