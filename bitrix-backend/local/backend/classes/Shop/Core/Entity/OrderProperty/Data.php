<?php

namespace Shop\Core\Entity\OrderProperty;

use GraphQL\Type\Definition\InputObjectType;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Shop\Core\Entity\OrderProperty;

class Data extends OrderProperty
{
    public function getAttrType()
    {
        return 'json';
    }

    public function getGraphqlFields()
    {
        return [
            'paramInt' => Types::int(),
            'paramString' => Types::string(),
            'paramArray' => Types::JSON(),
        ];
    }

    public function getGraphqlType()
    {
        return new ObjectType([
            'name' => 'OrderData',
            'fields' => $this->getGraphqlFields()
        ]);
    }

    public function getGraphqlInputType()
    {
        return new InputObjectType([
            'name' => 'OrderDataInput',
            'fields' => $this->getGraphqlFields()
        ]);
    }
}


