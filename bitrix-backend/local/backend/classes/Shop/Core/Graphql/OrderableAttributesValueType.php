<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Entity\OrderAttributeValue;
use Shop\Core\Lib\IOrderAttribute;

class OrderableAttributesValueType extends ObjectType
{
    public function getOrderAttrs()
    {
        return $this->container->getOrderAttributesService()->getAttributes();
    }

    public function getGraphqlType(IOrderAttribute $attr)
    {
        if ($attr->isMultiple()) {
            return Types::listOf($attr->getGraphqlType());
        } else {
            return $attr->getGraphqlType();
        }
    }

    public function resolveField($parent, $args, $context, ResolveInfo $info)
    {
        $attrName = $info->fieldName;

        if (preg_match('/\_STRING$/', $attrName)) {
            $attrName = preg_replace('/\_STRING$/', '', $attrName);
            $valueField = 'VIEW';
        } else {
            $valueField = 'VALUE';
        }

        /** @var OrderAttributeValue $attrResult */
        $attrResult = $parent[$attrName];

        if ($attrResult) {
            return $attrResult->getValue($valueField);
        }
    }
}
