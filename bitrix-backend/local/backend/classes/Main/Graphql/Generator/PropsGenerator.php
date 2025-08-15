<?php

namespace Main\Graphql\Generator;

use GraphQL\Type\Definition\ObjectType;
use Main\DI\Containerable;

abstract class PropsGenerator
{
    use Containerable;

    public $entityType;
    public $modelClass;
    public $view = [];
    public $allowedProps = [];

    function __construct($entityType, $modelClass, $view, $allowedProps = [])
    {
        $this->entityType = $entityType;
        if ($modelClass)
            $this->modelClass = $modelClass;
        $this->view = $view;
        $this->allowedProps = $allowedProps;
    }

    abstract function getProps();

    abstract function getFieldsInfo($props);

    function getFilteredProps()
    {
        return array_filter($this->getProps(), function ($prop) {
            return empty($this->allowedProps) || in_array($prop['code'], $this->allowedProps);
        });
    }

    function getResultFields()
    {
        $props = $this->getFilteredProps();
        if (!empty($props)) {
            return $this->getFieldsInfo($props);
        }
        return [];
    }

    function getResultType($typeSuffix = 'Props')
    {
        $props = $this->getFilteredProps();
        if (!empty($props)) {
            return $this->getFieldsType($props, $typeSuffix);
        }
    }

    function getFieldsType($props, $typeSuffix = 'Props')
    {
        if (!empty($props)) {
            $fields = $this->getFieldsInfo($props);
            if (!empty($fields)) {
                return new ObjectType([
                    'name' => $this->entityType . $typeSuffix,
                    'fields' => $fields
                ]);
            }
        }
    }
}

