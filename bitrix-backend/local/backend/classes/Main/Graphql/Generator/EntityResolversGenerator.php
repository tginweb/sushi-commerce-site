<?php

namespace Main\Graphql\Generator;

use GraphQL\Type\Definition\ObjectType;
use Main\DI\Containerable;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Type\Query\QueryNavInputType;
use Main\Graphql\Types;
use Main\Model\Response;

class EntityResolversGenerator extends ResolversGenerator
{
    use Containerable;

    public string $modelTypeName;
    public string $modelClass;

    public string $modelTypeClass;
    public string $modelInputTypeClass;
    public string $modelCreateTypeClass;
    public string $modelUpdateTypeClass;

    public string $queryClass;
    public string $filterTypeClass;

    public mixed $queryProcessor;

    public bool $activeOnly = true;

    function getMutationMap()
    {
        return [
            'create' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->getMutationPayloadedType($this->getModelMutationTypeName(), Types::get($this->modelTypeClass)),
                    'args' => [
                        'model' => Types::getNonNull($this->getModelCreateTypeClass()),
                    ],
                    'resolve' => [$this, 'mutationCreate'],
                ];
            },
            'update' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->getMutationPayloadedType($this->getModelMutationTypeName(), Types::get($this->modelTypeClass)),
                    'args' => [
                        'id' => Types::nonNull(Types::int()),
                        'model' => Types::getNonNull($this->getModelUpdateTypeClass()),
                    ],
                    'resolve' => [$this, 'mutationUpdate'],
                ];
            },
            'delete' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::boolean(),
                    'args' => [
                        'id' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutationDelete'],
                ];
            },
        ];
    }

    function getQueryMap()
    {
        return [
            'single' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::getNonNull($this->modelTypeClass),
                    'args' => $this->getQuerySingleArgs(Types::get($this->filterTypeClass)),
                    'cache' => $this->getQueryCacheConfig($queryName),
                    'resolve' => [$this, 'querySingle'],
                ];
            },
            'list' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull($this->modelTypeClass)),
                    'args' => $this->getQueryListArgs(Types::get($this->filterTypeClass)),
                    'cache' => $this->getQueryCacheConfig($queryName),
                    'resolve' => [$this, 'queryList'],
                ];
            },
            'recordset' => function ($queryName, $queryParams) {
                return [
                    'type' => new ObjectType([
                        'name' => $this->getModelTypeName() . 'Recordset',
                        'fields' => [
                            'info' => Types::getNonNull(QueryInfoType::class),
                            'nodes' => Types::nonNullListOf(Types::getNonNull($this->modelTypeClass)),
                        ]
                    ]),
                    'args' => $this->getQueryListArgs(Types::get($this->filterTypeClass)),
                    'cache' => $this->getQueryCacheConfig($queryName),
                    'resolve' => [$this, 'queryRecordset'],
                ];
            },
        ];
    }

    public function getQuerySingleArgs($filterType)
    {
        return [
            'id' => Types::int(),
            'where' => Types::JSON(),
            'filter' => $filterType,
            'nav' => Types::get(QueryNavInputType::class),
        ];
    }

    public function getQueryListArgs($filterType)
    {
        return [
            'id' => Types::int(),
            'where' => Types::JSON(),
            'filter' => $filterType,
            'nav' => Types::get(QueryNavInputType::class),
            'nocache' => Types::boolean(),
        ];
    }

    function getModelTypeName()
    {
        if (isset($this->modelTypeName)) {
            return $this->modelTypeName;
        }

        $parts = explode('\\', $this->modelTypeClass);
        $classTypeName = $parts[count($parts) - 1];
        $this->modelTypeName = preg_replace('/Type$/', '', $classTypeName);
        return $this->modelTypeName;
    }

    function setModelTypeName($name)
    {
        $this->modelTypeName = $name;
        return $this;
    }



    function getModelMutationTypeName()
    {
        return $this->getModelTypeName() . 'Mutation';
    }

    function getModelCreateTypeClass()
    {
        return $this->modelInputTypeClass ?? $this->modelCreateTypeClass ?? $this->modelUpdateTypeClass ?? null;
    }

    function setModelCreateTypeClass($class)
    {
        $this->modelCreateTypeClass = $class;
        return $this;
    }

    function getModelUpdateTypeClass()
    {
        return $this->modelInputTypeClass ?? $this->modelUpdateTypeClass ?? $this->modelCreateTypeClass ?? null;
    }

    function setModelUpdateTypeClass($class)
    {
        $this->modelUpdateTypeClass = $class;
        return $this;
    }

    function querySingle($rootValue, $args, $ctx)
    {
        return null;
    }

    function queryList($rootValue, $args, $ctx)
    {
        return [];
    }

    function queryRecordset($rootValue, $args, $ctx)
    {
        return [
            'nodes' => [],
            'info' => [
                'total' => 0,
                'page' => 0,
                'pages' => 0,
                'limit' => 0,
                'nextPage' => 0,
            ]
        ];
    }

    function queryProcess($query)
    {
        if (isset($this->queryProcessor)) {
            $cb = $this->queryProcessor;
            $cb($query);
        }
        return $query;
    }

    function mutationCreate($rootValue, $args, $ctx, $info, Response $response)
    {
        return null;
    }

    function mutationUpdate($rootValue, $args, $ctx, $info, Response $response)
    {
        return null;
    }

    function mutationDelete($rootValue, $args, $ctx, $info, Response $response)
    {
        return null;
    }

    function setModelClass($class)
    {
        $this->modelClass = $class;
        return $this;
    }

    function setModelTypeClass($class)
    {
        $this->modelTypeClass = $class;
        return $this;
    }

    function setModelInputTypeClass($class)
    {
        $this->modelInputTypeClass = $class;
        $this->modelCreateTypeClass = $class;
        $this->modelUpdateTypeClass = $class;
        return $this;
    }

    function setQueryActiveOnly($val = true)
    {
        $this->activeOnly = $val;
        return $this;
    }

    function setQueryProcessor($query)
    {
        $this->queryProcessor = $query;
        return $this;
    }

    function setFilterTypeClass($class)
    {
        $this->filterTypeClass = $class;
        return $this;
    }
}
