<?php

namespace Shop\Core\Graphql;

use Main\DI\Containerable;
use Main\Graphql\Type\InputType;
use Main\Graphql\Types;
use Shop\Core\Lib\IOrderAttribute;

class OrderAttributesValueInput extends InputType
{
    use Containerable;

    const NAME = 'OrderAttributesValueInput';

    public function getFieldsInfo()
    {
        $fields = [];

        /* @var IOrderAttribute $attr */
        foreach ($this->getOrderAttrs() as $attr) {
            $type = $this->getAttrType($attr);
            if ($type) {
                $fields[$attr['CODE']] = $type;
            }
        }

        return $fields;
    }

    public function getOrderAttrs()
    {
        return $this->container->getOrderAttributesService()->getAttributes();
    }

    public function getAttrType(IOrderAttribute $attr)
    {
        if ($attr->isMultiple()) {
            return Types::nonNullListOf($attr->getGraphqlInputType());
        } else {
            return $attr->getGraphqlInputType();
        }
    }
}
