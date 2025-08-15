<?php

namespace Shop\Pub\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;
use Shop\Core\Graphql\FavItemType;

class FavResolver extends ResolversGenerator
{
    public $ns = 'sale_fav_';

    function getQueryMap()
    {
        return [
            'state' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(FavItemType::class)),
                    'resolve' => [$this, 'queryState']
                ];
            },
        ];
    }

    function getMutationResultType()
    {
        return $this->getMutationPayloadedDefaultType('', Types::listOf(Types::getNonNull(FavItemType::class)));
    }

    function getMutationMap()
    {
        return [
            'add' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->getMutationResultType(),
                    'args' => [
                        'basketId' => Types::int(),
                        'productId' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutationAdd']
                ];
            },
            'remove' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->getMutationResultType(),
                    'args' => [
                        'basketId' => Types::int(),
                        'productId' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutationRemove']
                ];
            },
            'clear' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->getMutationResultType(),
                    'args' => [
                        'action' => Types::string(),
                        'itemId' => Types::int(),
                        'productId' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutationClear']
                ];
            },
        ];
    }

    function queryState($rootValue, $args)
    {
        return $this->container->getFavService()->getBasket();
    }

    function mutationAdd($rootValue, $args, $ctx, $info, Response $response)
    {
        $res = $this->getResponse();
        $service = $this->container->getFavService();
        $res->setPayload($service->mutationAdd($args));
        return $res->getGraphqlJson();
    }

    function mutationRemove($rootValue, $args, $ctx, $info, Response $response)
    {
        $res = $this->getResponse();
        $service = $this->container->getFavService();
        $res->setPayload($service->mutationRemove($args));
        return $res->getGraphqlJson();
    }

    function mutationClear($rootValue, $args, $ctx, $info, Response $response)
    {
        $res = $this->getResponse();
        $service = $this->container->getFavService();
        $service->mutationClear();
        return $res->getGraphqlJson();
    }
}



