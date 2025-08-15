<?php

namespace Main\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Type\SettingsItemType;
use Main\Graphql\Types;

class SettingsResolver extends ResolversGenerator
{
    public $ns = 'settings_';

    function getQueryMap()
    {
        return [
            'list' => function () {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(SettingsItemType::class)),
                    'resolve' => [$this, 'querySettingsList']
                ];
            }
        ];
    }

    function querySettingsList($rootValue, $args)
    {
        $settings = $this->container->getSettingsService()->getClientPublicSettings();

        $result = [];
        foreach ($settings as $key => $value) {
            $result[] = [
                'key' => $key,
                'value' => $settings[$key]
            ];
        }
        return $result;
    }
}



