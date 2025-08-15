<?php

$commonConfig = include('admin.php');

$envConfig = [
    'DEBUG' => [
        'ENABLE' => true
    ],

    'GRAPHQL' => [
        'CACHE_ENABLE' => true
    ],

    'IBLOCK' => [
        'CACHE' => true,

        'PROPS' => [
            'CACHE' => true,
            'ENUM_CACHE' => false,
        ],
    ]
];

return array_replace_recursive($commonConfig, $envConfig);
