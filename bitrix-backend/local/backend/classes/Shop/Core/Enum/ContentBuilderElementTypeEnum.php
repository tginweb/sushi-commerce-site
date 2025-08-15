<?php

namespace Shop\Core\Enum;

use Main\Graphql\Enum\BaseEnum;

class ContentBuilderElementTypeEnum extends BaseEnum
{
    public static function options()
    {
        return [
            'html' => 'html',
            'component' => 'компонент',
        ];
    }
}
