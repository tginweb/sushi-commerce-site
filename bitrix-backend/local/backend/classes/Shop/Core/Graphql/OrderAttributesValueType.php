<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Types;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributesValueType extends OrderableAttributesValueType
{
    const NAME = 'OrderAttributesValue';

    public function getFieldsInfo()
    {
        $fields = [];

        /* @var IOrderAttribute $attr */
        foreach ($this->getOrderAttrs() as $attr) {
            $type = $this->getGraphqlType($attr);
            //if ($attr['CODE']==='TRANSPORT_TYPE') die('ddd');
            if ($type) {
                $fields[$attr['CODE']] = $type;
                if ($attr->haveView()) {
                    if ($attr->isMultiple()) {
                        $fields[$attr['CODE'] . '_STRING'] = Types::nonNullListOf(Types::string());
                    } else {
                        $fields[$attr['CODE'] . '_STRING'] = Types::string();
                    }
                }
            }
        }

        return $fields;
    }
}
