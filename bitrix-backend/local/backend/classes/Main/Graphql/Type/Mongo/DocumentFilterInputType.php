<?php

namespace Main\Graphql\Type\Mongo;

use Main\Graphql\Type\Query\QueryFilterInputType;

class DocumentFilterInputType extends QueryFilterInputType
{
    const NAME = 'DocumentFilterInput';

    function getFieldsInfo()
    {
        $arFields = [

        ];

        return $arFields;
    }
}
