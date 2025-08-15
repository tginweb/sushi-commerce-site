<?php

namespace Shop\Pub\Api\Resolver;

use Main\Graphql\Generator\HLEntityResolversGenerator;
use Main\Graphql\Types;
use Shop\Core\Entity\ClientCard;
use Shop\Core\Graphql\ClientCardFilterInputType;
use Shop\Core\Graphql\ClientCardType;
use TG\Main\Helper;

class ClientCardResolver extends HLEntityResolversGenerator
{
    public $ns = 'sale_client_card_';

    public string $modelClass = ClientCard::class;
    public string $modelTypeClass = ClientCardType::class;
    public string $filterTypeClass = ClientCardFilterInputType::class;

    function getQueryMap()
    {
        return parent::getQueryMap() + [
                'fetch' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::get(ClientCardType::class),
                        'args' => [
                            'isScope' => Types::boolean(),
                            'refetch' => Types::boolean(),
                        ],
                        'resolve' => [$this, 'queryFetch'],
                    ];
                },
                'apply_by_phone' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::get(ClientCardType::class),
                        'args' => [
                            'phone' => Types::string(),
                        ],
                        'resolve' => [$this, 'applyByPhone']
                    ];
                },
            ];
    }

    function applyByPhone($rootValue, $args)
    {
        $phone = \Main\Helper\Format::validateMobile($args['phone']);

        if ($phone) {
            $card = $this->container->getSaleClientCardService()->findOrCreateByPhone($phone);
            if ($card) {
                $_SESSION['SALE_GUEST_CLIENT_PHONE'] = $phone;
                $card->fetch();
                return $card;
            }
        }
    }

    function queryFetch($rootValue, $args)
    {
        $card = $this->container->getSaleClientCardService()->getCurrentUserCard();

        if ($card) {
            if (!$args['isScope']) {
                $card->fetch($args['refetch']);
            }
        }

        return $card ?: null;
    }
}



