<?php

namespace Offer\Pub\Api;

use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Graphql\Types;
use Offer\Core\Graphql\OfferType;

class Resolver extends ElementResolversGenerator
{
    public $ns = 'offer_';

    function getQueryMap()
    {
        return parent::getQueryMap() + [
                'user_list' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::nonNullListOf(Types::getNonNull(OfferType::class)),
                        'resolve' => [$this, 'queryUserList']
                    ];
                },
                'common_list' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::nonNullListOf(Types::getNonNull(OfferType::class)),
                        'resolve' => [$this, 'queryCommonList'],
                        'cache' => true
                    ];
                },
            ];
    }

    function queryUserList($rootValue, $args)
    {
        $service = $this->container->getOfferService();
        return $service->getIndividualOffers();
    }

    function queryCommonList($rootValue, $args)
    {
        $service = $this->container->getOfferService();
        return $service->getCommonOffers();
    }
}



