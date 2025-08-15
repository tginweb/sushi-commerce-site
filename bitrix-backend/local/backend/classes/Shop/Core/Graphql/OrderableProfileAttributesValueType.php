<?php

namespace Shop\Core\Graphql;

class OrderableProfileAttributesValueType extends OrderableAttributesValueType
{
    const NAME = 'OrderProfileAttributesValue';

    public function getFieldsInfo()
    {
        $fields = [];
        foreach ($this->getOrderAttrs()->filterIsProfile() as $attr) {
            $fields[$attr->getCode()] = $attr->getGraphqlType();
        }
        return $fields;
    }
}
