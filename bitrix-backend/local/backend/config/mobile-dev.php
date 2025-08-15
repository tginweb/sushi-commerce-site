<?php


$commonConfig = include('mobile.php');

$envConfig = [
    'APP' => [
        'SITE_URL' => 'http://dev.sushi-new.prod.loc',
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
    ],

    'SERVICE_1C' => [
        'SEND_ORDER' => true,
    ],
];

return array_replace_recursive($commonConfig, $envConfig);
