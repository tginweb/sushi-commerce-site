<?php

namespace Main\Graphql\Wrapper;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Error\BaseError;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\ResponseType;
use Main\Graphql\Types;
use Main\Helper\Str;
use Main\Model\Response;

class MutationWrapper
{
    public $info;

    public $baseTypeName;

    function __construct($info, $queryName = null)
    {
        $this->info = $info;

        if ($queryName) {
            $this->info['queryName'] = $queryName;
        }
    }

    public function getDefinition()
    {
        return [
            'type' => $this->info['payload'] ? $this->createType() : Types::getNonNull(ResponseType::class),
            'args' => $this->info['args'] ?? [],
            'log' => $this->info['log'] ?? [],
            'resolve' => $this->createResolve(),
            'parseValue' => $this->info['parseValue'] ?? null,
        ];
    }

    public function createType()
    {
        return new ObjectType([
            'name' => $this->getResultTypeName(),
            'fields' => $this->createResultFields(),
        ]);
    }

    public function getResultTypeName()
    {
        return $this->getBaseTypeName() . 'Result';
    }

    public function getBaseTypeName()
    {
        if (!$this->baseTypeName) {
            $this->baseTypeName = Str::camelCase($this->info['queryName'], true);
        }
        return $this->baseTypeName;
    }

    public function createResultFields()
    {
        $fields = [
            'success' => Types::boolean(),
            'errors' => Types::nonNullListOf(Types::nonNull(Types::getType('ErrorInterface'))),
            'error' => Types::getType('ErrorInterface'),
            'state' => Types::getNonNull(ResponseStateType::class)
        ];

        $payload = $this->createResultPayload();

        if ($payload) {
            $fields['payload'] = $payload;
        } else {
            $fields['payload'] = Types::JSON();
        }

        return $fields;
    }

    public function createResultPayload()
    {
        $fields = $this->info['payload'] ?? null;
        if ($fields) {
            return is_array($fields) ? new ObjectType([
                'name' => $this->getResultPayloadTypeName(),
                'fields' => $fields
            ]) : $fields;
        }
    }

    public function getResultPayloadTypeName()
    {
        return $this->getResultTypeName() . 'Payload';
    }

    public function createResolve()
    {
        return function ($root, array $args, $context, ResolveInfo $resolveInfo) {
            $response = new Response();

            try {
                $result = call_user_func_array($this->info['resolve'], [
                    $root,
                    $args,
                    $context,
                    $resolveInfo,
                    $response
                ]);

                if (!empty($result)) {
                    $response->assignPayload($result);
                }
            } catch (BaseError $e) {
                $response->addException($e);
            }

            $response->commit();

            return $response->getGraphqlJson();
        };
    }
}
