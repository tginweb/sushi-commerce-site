<?php

namespace Main\Service;

use TG\Main\Helper;

class EntityService extends BaseService
{
    function getEntityTypesInfo()
    {
        $types = $this->getEntityTypes();
        $result = [];
        foreach ($types as $typeName => $type) {
            if ($type['storage']) {
                $exportData = [
                    'type' => $typeName,
                    'driver' => $type['driver']
                ];
                $exportData['scopes']['public'] = $type['scopes']['public'];
                $exportData = array_filter($exportData);
                $result[$typeName] = $exportData;
            }
        }
        return $result;
    }

    function getEntityTypes()
    {
        return $this->container->applyFiltersCached('entity:types', []);
    }

    function getEntityPublicUrl($type, $entity, $action = null, $scope = 'public')
    {
        return $this->getEntityUrl($type, $entity, $action, $scope);
    }

    function getEntityUrl($type, $entity, $action, $scope)
    {
        $action = $action ?? 'view';
        $types = $this->getEntityTypes();
        $urlTemplate = \Main\Helper\Common::arrayGetNestedValue($types, [$type, 'scopes', $scope, 'url', $action]);
        if ($urlTemplate) {
            $vars = ['ENTITY' => $entity->getCompileVars()];
            return $this->container->getCompilerService()->compileVars($urlTemplate, $vars);
        }
    }

    function getEntityAdminUrl($type, $entity, $action = null, $scope = 'admin')
    {
        return $this->getEntityUrl($type, $entity, $action, $scope);
    }

    function getEntityTypeInfo($type)
    {
        $typeData = $this->getEntityType($type);
        return $typeData['info'];
    }

    function getEntityType($type)
    {
        $types = $this->getEntityTypes();
        return !empty($types[$type]) ? $types[$type] : null;
    }

    function findOneAsOperation($type, $sid)
    {
        $typeInfo = $this->getEntityType($type);
        if ($typeInfo && !empty($typeInfo['operation']))
            return $this->findOneBySid($type, $sid);
    }

    function findOneBySid($type, $sid)
    {
        return $this->findOne($type, $sid, 'UF_SID');
    }

    function findOne($type, $value, $by = 'ID')
    {
        $typeInfo = $this->getEntityType($type);
        if (!$typeInfo || empty($typeInfo['findOne'])) return null;
        return $typeInfo['findOne']($value, $by);
    }

    function getEntityDataloader($entityType, $entityId, $dataloaders)
    {
        $entityTypeData = $this->container->getEntityService()->getEntityType($entityType);
        return $entityId && $entityTypeData && $entityTypeData['dataloader'] ? $entityTypeData['dataloader']($entityId, $dataloaders) : null;
    }
}


