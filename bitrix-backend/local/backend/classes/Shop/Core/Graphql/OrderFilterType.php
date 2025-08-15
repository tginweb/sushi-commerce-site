<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\Query\QueryFilterInputType;
use Main\Graphql\Types;

class OrderFilterType extends QueryFilterInputType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderFilter',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo']
        ]);
    }

    function getFieldsInfo()
    {
        return [
            'ID' => [
                'type' => Types::IntFilter(),
            ],
            'ACCOUNT_NUMBER' => [
                'type' => Types::StringFilter(),
            ],
            'PAYED' => [
                'type' => Types::boolean(),
            ],
            'USER_ID' => [
                'type' => Types::IntFilter(),
            ],
            'EMAIL' => [
                'type' => Types::StringFilter(),
            ],
            'PHONE' => [
                'type' => Types::StringFilter(),
            ],
            'ELEMENT_NAME' => [
                'type' => Types::StringFilter(),
            ],
            'SECTION_ID' => [
                'type' => Types::IntFilter(),
            ],
            'PAY_SYSTEM_ID' => [
                'type' => Types::IntFilter(),
            ],
            'STATUS_ID' => [
                'type' => Types::StringFilter(),
            ],
            'CANCELED' => [
                'type' => Types::boolean(),
            ],
            'DATE_YEAR' => [
                'type' => Types::Int(),
            ],
            'DATE_MONTH' => [
                'type' => Types::Int(),
            ],
            'DATE_DAY' => [
                'type' => Types::Int(),
            ],

            'DATE_PAYED_YEAR' => [
                'type' => Types::Int(),
            ],
            'DATE_PAYED_MONTH' => [
                'type' => Types::Int(),
            ],
            'DATE_PAYED_DAY' => [
                'type' => Types::Int(),
            ],

            'PROP_FIO' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_EMAIL' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_PHONE' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_PRODUCT_SECTIONS' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_CONTRACT_NUM' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_EDU_GROUP_NUM' => [
                'type' => Types::StringFilter(),
            ],
            'PROP_STUDENT_FIO' => [
                'type' => Types::StringFilter(),
            ],

            'MODE' => [
                'type' => Types::string(),
            ],

        ];
    }
}
