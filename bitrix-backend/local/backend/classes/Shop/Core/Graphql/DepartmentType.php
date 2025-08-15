<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Types;

class DepartmentType extends HLEntityType
{
    const NAME = 'SaleDepartment';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'SERVICE_ID' => Types::int(),
                'NAME' => Types::string(),
                'ADDRESS' => Types::string(),
            ];
    }
}
