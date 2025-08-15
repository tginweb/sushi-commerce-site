<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;

class EntityResolver extends ResolversGenerator
{
    public $ns = 'entity_';

    function getQueryMap()
    {
        return [
            'types_info' => function () {
                return [
                    'type' => Types::JSON(),
                    'resolve' => [$this, 'queryTypesInfo']
                ];
            }
        ];
    }

    function queryTypesInfo($rootValue, $args, $ctx, $info)
    {
        $scopes = $this->container->getApp()->getRegisterScopes();

        if (!in_array('public', $scopes)) {
            $scopes[] = 'public';
        }

        $entityTypes = $this->container->getEntityService()->getEntityTypes();

        $result = [];

        foreach ($entityTypes as $entityType => $entityInfo) {

            $resultType = $entityInfo['INFO'] ?? ['NAME' => ''];

            $resultType['scopes'] = [];

            if (isset($entityInfo['scopes'])) {
                foreach ($scopes as $scope) {
                    if (isset($entityInfo['scopes'][$scope])) {
                        $resultType['scopes'][$scope] = $entityInfo['scopes'][$scope];
                    }
                }
            }

            $result[$entityType] = $resultType;
        }

        return $result;
    }
}



