<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Type\Entity\EntityInfoType;
use Main\Graphql\Types;

class EntityInfoResolver extends ResolversGenerator
{
    public $ns = 'entity_info_';

    function getQueryMap()
    {
        return [
            'list' => function () {
                return [
                    'type' => Types::listOf(Types::get(EntityInfoType::class)),
                    'resolve' => [$this, 'queryList']
                ];
            }
        ];
    }

    function queryList($rootValue, $args)
    {
        return $this->container->getEntityService()->getEntityTypesInfo();
    }
}



