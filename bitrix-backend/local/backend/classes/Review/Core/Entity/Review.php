<?php

namespace Review\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class Review extends ElementModel
{
    public function getUrl()
    {
        return '/review/' . $this['ID'];
    }

    static function getPropsInfo()
    {
        return [
            'AUTHOR_USER_ID' => ['view' => true],
            'AUTHOR_NAME' => ['view' => true],
            'ELEMENT_ID' => ['view' => true],
            'CONTEXT_ID' => ['view' => true],
            'RATING' => ['view' => true],
        ];
    }
}
