<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class PersonTypeType extends EntityType
{
    const NAME = 'PersonType';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'SORT' => Types::int(),
            'CODE' => Types::string(),
            'RESTRICTED' => Types::boolean(),
            'IS_COMPANY' => Types::boolean(),
        ];
    }

    public function resolve_IS_COMPANY($parent, $args, $ctx)
    {
        return $parent->isCompany();
    }
}
