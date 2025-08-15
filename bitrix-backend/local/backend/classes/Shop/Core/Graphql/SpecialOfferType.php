<?php

namespace Shop\Core\Graphql;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SpecialOfferType extends ObjectType
{
    const NAME = 'SpecialOffer';

    public function getFieldsInfo()
    {
        return [
            'MIN_PRICE' => Types::int(),
            'MODE' => Types::string(),
            'ELEMENT_ID' => Types::int(),
            'ELEMENT' =>Types::getType('ProductElement'),
            'TYPE' => Types::string(),
            'TYPE_INFO' => Types::get(SpecialOfferTypeType::class),
        ];
    }

    public function resolve_TYPE_INFO($parent, $args, $context, ResolveInfo $info)
    {
        $schema = [
            'special' => [
                'NAME' => 'Выгодное предложение',
                'CODE' => 'special',
                'COLOR' => '#e94949',
            ],
            'recommendation' => [
                'NAME' => 'Рекомендуем',
                'CODE' => 'recommendation',
                'COLOR' => 'rgb(253 139 35)',
            ],
        ];
        return $schema[$parent['TYPE']];
    }

    public function resolve_ELEMENT($parent, $args, $context, ResolveInfo $info)
    {
        return $context['dataloader']['element']->load($parent['ELEMENT_ID'] . '.list');
    }
}
