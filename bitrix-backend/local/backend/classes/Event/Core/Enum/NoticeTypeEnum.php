<?php

namespace Event\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class NoticeTypeEnum extends BaseEnum
{
    static function options()
    {
        return [
            'alert' => 'Алерт',
            'dialog' => 'Диалог',
        ];
    }
}
