<?php


$commonConfig = include('mobile.php');

$envConfig = [
    'APP' => [
        'SITE_URL' => 'https://irkutsk.sushi-studio.ru',
    ],
    'RATE' => [
        'DISABLE' => false
    ],
    'CAPTCHA' => [
        'DISABLE' => true
    ],
    'DEBUG' => [
        'ENABLE' => true
    ],
    'GRAPHQL' => [
        'CACHE_ENABLE' => false
    ],

    'IBLOCK' => [
        'CACHE' => true,
        'PROPS' => [
            'CACHE' => false,
            'ENUM_CACHE' => false,
        ],
        'SCHEMA' => [
            66 => [
                'ELEMENT_CLASS' => '',
                'SECTION_CLASS' => '',
            ],
            69 => [
                'ELEMENT_CLASS' => \Menu\Core\Entity\MenuItemMobile::class,
                'SECTION_CLASS' => \Menu\Core\Entity\MenuItemMobileSection::class,
            ],
        ]
    ],

    'SERVICE_1C' => [
        'SEND_ORDER' => true,
    ],
];


return array_replace_recursive($commonConfig, $envConfig);
