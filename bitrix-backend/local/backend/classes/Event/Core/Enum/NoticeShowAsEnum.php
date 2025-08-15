<?php

namespace Event\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class NoticeShowAsEnum extends BaseEnum
{
    static function options()
    {
        return [
            'common' => 'Общие',
            'individual' => 'Индвидиуальные',
        ];
    }
}
